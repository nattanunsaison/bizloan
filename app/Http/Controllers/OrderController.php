<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{order,contractors,dealers,ScfReceiveAmountHistory,ReceiveAmountDetail};
use App\Events\{ReceiveAmountConfirm,DeleteAmountConfirm};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    //Route::get('/dashboard',[OrderController::class, 'orderList']);
    public function orderList(){
        $orders = order::whereRelation('dealer','dealer_type',\App\Enum\DealerType::Transformer)->get();
        return view('list_order',[
            'orders'=>$orders
        ]);
    }

    //Route::get('/unpaid_up_orders',[OrderController::class, 'unpaidupOrders']);
    public function unpaidupOrders(){
        $scf_paid_up_orders = ScfReceiveAmountHistory::query()
                    ->whereNotNull('paid_up_ymd')
                    ->get()->unique('order_id')->pluck('order_id');
        $orders = order::whereRelation('dealer','dealer_type',\App\Enum\DealerType::Transformer)
                    ->whereNull('paid_up_ymd')
                    ->whereNull('canceled_at')
                    ->whereNotIn('id',$scf_paid_up_orders)
                    ->get();
        //return order::find(71739)->receive_records->sum('receive_amount');
        return view('unpaid_order',[
            'orders'=>$orders
        ]);
    }

    //Route::get('/repayment',[OrderController::class, 'repayment'])->name('order.repayment');
    public function repayment(){
        $scf_paid_up_orders = ScfReceiveAmountHistory::query()
                    ->whereNotNull('paid_up_ymd')
                    ->get()->unique('order_id')->pluck('order_id');
        $order = order::query()
                    ->whereNotIn('id',$scf_paid_up_orders)
                    ->where('id',request()->order_id)
                    ->get()->first();
        //return $latest_receive_detail = $order->receive_records->last()->receive_amount_detail->delayPenalty();
        //return $latest_receive_detail->delayPenalty();
        //return $latest_receive_detail->order;
        //return count($order->receive_records);//->last();

        if(is_null($order))
            return 'Not found or order might be repaid';
        return view('repayment',[
            'order'=>$order,
        ]);
    }

    //Route::get('/repayment_list',[OrderController::class, 'listRepayment'])->name('repayment.list');
    public function listRepayment(){
        $records = ScfReceiveAmountHistory::all();
        //return $records->skip(1)->first()->receive_amount_detail;
        return view('repayment_list',[
            'records'=>$records,
        ]);
    }

    //Route::get('/store/receive_amount',[OrderController::class, 'storeReceiveAmount'])->name('repayment.receive');
    public function storeReceiveAmount(Request $request){
        //return request();
        //have to validate?
        if($request->is_exempt_late_charge)
            $exempt_late_charge = $request->delay_penalty;
        else
            $exempt_late_charge = null;

        if($request->is_exempt_interest)
            $exempt_interest = $request->interest;
        else
            $exempt_interest = null;
        
        $buyer_receipt_number = $this->getBuyerRTNumber($request->receive_date);
        $seller_receipt_number = null;
        if($request->outstanding_principal == 0){
            $paid_up_ymd = $request->receive_date;
            $seller_receipt_number = $this->getSellerRTNumber($request->receive_date);
        }
        else{
            $paid_up_ymd = null;
        }
        //return $seller_receipt_number;
        //return $exempt_interest;
        //return $paid_up_ymd;
        //return $exempt_late_charge;
        //return str_replace(',', '',$request->receive_amount);
        $receive_amount = str_replace(',', '',$request->receive_amount);
        $paid_principal = $receive_amount - $request->interest_to_pay - $request->delay_penalty_to_pay;
        //return $paid_principal;
        $record = ScfReceiveAmountHistory::create([
            'order_id'=>$request->order_id,
            'comment'=>$request->comment,
            'receive_ymd'=>$request->receive_date,
            'paid_up_ymd'=>$paid_up_ymd,
            'receive_amount'=>$receive_amount,
            'exemption_late_charge'=>$exempt_late_charge,
            'exemption_interest'=>$exempt_interest,
            'create_user_id'=>auth()->user()->id,
            'net_pay_amount'=>$request->payback_amount_to_supplier,
            'seller_receipt_number'=>$seller_receipt_number,
            'buyer_receipt_number'=>$buyer_receipt_number
        ]);
        //receive amount detail can call via event
        //$order = order::find(request()->order_id);
        
        $detail = ReceiveAmountDetail::create([
            'scf_receive_amount_history_id'=>$record->id,
            'dealer_type',
            'installment_number',
            'switched_date',
            'rescheduled_date',
            'repayment_ymd'=>$request->receive_date,
            'principal'=>$request->outstanding_balance_before,
            'interest'=>$request->interest,
            'late_charge'=>$request->delay_penalty,
            'paid_principal'=>$paid_principal,
            'paid_interest'=>$request->interest_to_pay,
            'paid_late_charge'=>$request->delay_penalty_to_pay,
            'total_principal',
            'total_interest',
            'total_late_charge',
            'waive_late_charge'=>$exempt_late_charge,
            'waive_interest'=>$exempt_interest,
            'contractor_id',
            'payment_id',
            'order_id',
            'installment_id',
            'dealer_id',
            'exceeded_occurred_amount'=>$request->exceeded_amount,
            'payback_amount'=>$request->payback_amount_to_supplier,
            'outstanding_balance'=>$request->outstanding_principal,
            'tax'=>$request->tax,
            'paid_tax'=>$request->tax_to_pay,
        ]);

        if($record){
            event(new ReceiveAmountConfirm($record));
            session(['success'=>true]);
        }
    }

    //Route::get('/dealer_scb_account,[OrderController::class,'dealerSCBAccount']);
    public function dealerSCBAccount(Request $request){
        $bank_account_details = DB::table('dealer_bank_account_details')
                            ->leftJoin('dealers','dealer_bank_account_details.tax_id','dealers.tax_id')
                            ->where('dealers.dealer_type',\App\Enum\DealerType::Transformer->value)
                            ->select('dealer_bank_account_details.*','dealers.id as dealer_id')
                            ->get();
        return view('dealer_scb_account',[
            'bank_account_details'=>$bank_account_details,
        ]);
    } 

    //Route::get('/receive_history',[OrderController::class,'receiveHistory']);
    public function receiveHistory(){
        $order_id = request()->order_id;
        $histories = ScfReceiveAmountHistory::where('order_id',$order_id)->get();
        return $histories->map(function($history){
            $history->receive_amount_format = number_format($history->receive_amount,2);
            return $history;
        });
    }

    //Route::post('/delete/latest_receive_history',[OrderController::class,'deleteLatestReceiveHistory']);
    public function  deleteLatestReceiveHistory(){
        $order_id = request()->order_id;
        $history = ScfReceiveAmountHistory::where('order_id',$order_id)->orderBy('id','desc')->first();
        $detail = ReceiveAmountDetail::where('scf_receive_amount_history_id',$history->id)->first()->delete();
        $history->deleted_user_id = auth()->user()->id;
        $history->delete_reasons = request()->delete_reasons;
        $history->save();
        $deleted = $history->delete();
        if($deleted){
            event(new DeleteAmountConfirm(1,$order_id));
            session(['success'=>true]);
        }
        
    }

    //Route::post('/delete/all_receive_history',[OrderController::class,'deleteAllReceiveHistory']);
    public function  deleteAllReceiveHistory(){
        $order_id = request()->order_id;
        $histories = ScfReceiveAmountHistory::where('order_id',$order_id)->get();
        ScfReceiveAmountHistory::where('order_id',$order_id)->update(['deleted_user_id'=>auth()->user()->id,'delete_reasons'=>request()->delete_reasons]);
        $history_ids = ScfReceiveAmountHistory::where('order_id',$order_id)->get()->pluck('id');
        $deleted = ScfReceiveAmountHistory::where('order_id',$order_id)->delete();
        $detail = ReceiveAmountDetail::whereIn('scf_receive_amount_history_id',$history_ids)->delete();
        if($deleted){
            event(new DeleteAmountConfirm(count($histories),$order_id));
            session(['success'=>true]);
        }
        
    }

    //Route::get('/delete_receive_history',[OrderController::class,'deleteReceiveHistoryList']);
    public function deleteReceiveHistoryList(){
        $order_id = request()->order_id;
        if(!is_null($order_id))
            $records = ScfReceiveAmountHistory::onlyTrashed()->get();
        else
            $records = ScfReceiveAmountHistory::onlyTrashed()->where('order_id',$order_id)->get();
        //$records->first()->receive_amount_detail()->withTrashed()->first(); //one to one relationship
        return view('repayment_list',[
            'records'=>$records,
        ]);
    }

    public function getBuyerRTNumber(String $ymd){ //Buyer receipt
		$now = Carbon::parse($ymd);
		$yearMonth = $now->copy()->isoFormat('YYYYMM');
        
		$latestRecord = ScfReceiveAmountHistory::whereNotNull('buyer_receipt_number')->where('receive_ymd','like',$yearMonth.'%')->get()->last();
		//return $latestRecord;
        if(is_null($latestRecord)){
            $runningNo = 1;
            //return 1;
        }
		else if(Carbon::parse($latestRecord->receive_ymd)->isoFormat('YYYYMM') != $yearMonth){
			$runningNo = 1;
            //return 2;
		}else{
			$runningNo = intval(substr($latestRecord->buyer_receipt_number,-3))+1;
            //return 3;
		}

		$filledZero = sprintf('%03d', $runningNo);;
		$fullRunningNo = 'RT-SCF'.$now->copy()->isoFormat('YYYYMM').''.$filledZero;	
		return $fullRunningNo;
	}

    public function getSellerRTNumber(String $ymd){ //Seller receipt only when paid up
		$now = Carbon::parse($ymd);
		$yearMonth = $now->copy()->isoFormat('YYYYMM');
        
		$latestRecord = ScfReceiveAmountHistory::whereNotNull('seller_receipt_number')->where('seller_receipt_number','like','%'.$yearMonth.'%')->get()->last();
		//return $latestRecord;
        if(is_null($latestRecord)){
            $runningNo = 1;
            //return 1;
        }
		else if(Carbon::parse($latestRecord->receive_ymd)->isoFormat('YYYYMM') != $yearMonth){
			$runningNo = 1;
            //return 2;
		}else{
			$runningNo = intval(substr($latestRecord->seller_receipt_number,-3))+1;
            //return 3;
		}

		$filledZero = sprintf('%03d', $runningNo);;
		$fullRunningNo = 'SCGI'.$now->copy()->isoFormat('YYYYMM').''.$filledZero;	
		return $fullRunningNo;
	}
}
