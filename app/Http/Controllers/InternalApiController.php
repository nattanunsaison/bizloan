<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
// use App\Events\{ReceiveAmountConfirm,DeleteAmountConfirm};
use App\Models\{order,contractors,Customer,dealers,ReceiveAmountHistory,ReceiveAmountDetail,User,
    ProductOffering,installments,installmentHistory};
use Illuminate\View\View;
use Illuminate\Support\Arr;
use App\Events\ReceiveAmountConfirm;

class InternalApiController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            return $next($request);
        });
    }

    public function existingCustomerInformation(Request $request){
        // return $request->contractor_id;
        $conid = $request->contractor_id;
        $customerData = contractors::where('status', 1)
                        ->where('id', $conid)
                        ->select(
                            'id', 'tax_id', 'th_company_name', 'en_company_name',
                            'owner_mobile_number', 'owner_email', 'address'
                        )
                        ->get()->first();
        return $customerData;
    }

    public function registerCustomer(Request $request){
        if($request->customer_status && $request->master_agreement){
            $ready_status = 1;
        }else{
            $ready_status = 0;
        }
        
        // return $request;
        $customer_create = Customer::create([
            'con_id'=>$request->con_id,
            'tax_id'=>$request->tax_id,
            'existing_customer'=>$request->existing_customer,
            'th_company_name'=>$request->th_company_name,
            'en_company_name'=>$request->en_company_name,
            'customer_address'=>$request->customer_address,
            'customer_email'=>$request->customer_email,
            'customer_phone_number'=>$request->customer_phone_number,
            'status'=>$request->customer_status,
            'business_loan_amount'=>$request->business_loan_amount,
            'offering_grade'=>$request->offering_grade,
            'kyc_pic'=>$request->staff_userid,
            'master_agreement'=>$request->master_agreement,
            'ready_status'=>$ready_status,
        ]);

        return $customer_create;
    }

    public function productInformation(Request $request){
        // return $request->offering_id;
        
        $product_information = ProductOffering::leftJoin('products','product_offerings.product_id','products.id')
                            ->where('product_offerings.id', '=', $request->offering_id)
                            ->select(
                                'product_offerings.id', 'product_offerings.offering_grade',
                                'product_offerings.interest_rate', 'product_offerings.delay_penalty_rate', 'product_offerings.discount_rate',
                                'products.product_code','products.product_name','products.terms','products.loan_amount'
                            )
                            ->get()->first();
        
        return $product_information;
    }
    
    public function duedateCalculation(Request $request){
        // return $request;
        // return $request->product_offering_id;
        $transfer_date = $request->transfer_date;
        $product_offering_id = $request->product_offering_id;
        
        $product_information = ProductOffering::leftJoin('products','product_offerings.product_id','products.id')
                            ->where('product_offerings.id', '=', $product_offering_id)
                            ->select(
                                'product_offerings.id', 'product_offerings.offering_grade',
                                'product_offerings.interest_rate', 'product_offerings.delay_penalty_rate', 'product_offerings.discount_rate',
                                'products.product_code','products.product_name','products.terms','products.loan_amount'
                            )
                            ->get()->first();
        
        $day_diff = $product_information->terms;
        $due_date = Carbon::createFromFormat('d/m/Y',$transfer_date)->addDays($day_diff)->format('d/m/Y');
        return $due_date;
    }
    
    //Route::post('/drawdown_input',[InternalApiController::class,'drawdownInput']);
    public function drawdownInput(Request $request){
        // return $request;

        $tax_id = $request->tax_id;
        $product_offering_id = $request->product_offering_id;
        $loan_amount = $request->loan_amount;
        $get_transfer_date = $request->transfer_date;
        $due_date = $request->due_date;
        $staff_userid = $request->staff_userid;

        $productInformation = ProductOffering::leftJoin('products','product_offerings.product_id','products.id')
                            ->where('product_offerings.id', '=', $product_offering_id)
                            ->select(
                                'product_offerings.id', 'product_offerings.offering_grade',
                                'product_offerings.interest_rate', 'product_offerings.delay_penalty_rate', 'product_offerings.discount_rate',
                                'products.product_code','products.product_name','products.terms','products.loan_amount'
                            )
                            ->get()->first();
                            
        $customerData = Customer::where('ready_status', 1)
                        ->where('tax_id', $tax_id)
                        ->select(
                            'id', 'tax_id', 'th_company_name', 'en_company_name',
                            'customer_phone_number', 'customer_email', 'customer_address'
                        )
                        ->get()->first();
        
        $interestRate = $productInformation->interest_rate;
        $delayPenaltyRate = $productInformation->delay_penalty_rate;
        $discountRate = $productInformation->discount_rate;
        $principal = $productInformation->loan_amount;
        $day_diff = $productInformation->terms;
        // $due_date = Carbon::parse($transfer_date)->addDays($day_diff);
        $transfer_date = Carbon::createFromFormat('d/m/Y',$get_transfer_date)->format('Ymd');
        $due_date = Carbon::createFromFormat('d/m/Y',$get_transfer_date)->addDays($day_diff)->format('Ymd');


        $interest = floor($interestRate/100/365*$principal*100*$day_diff)/100;

        $formatNow = Carbon::now()->isoFormat('YYYYMMDD-HHmm');

        $drawdown_id = 'BL'.$formatNow;
        
        try {
            DB::beginTransaction();
            $drawdown_input_create = order::create([
                'order_number'=>$drawdown_id,
                'customer_id'=>$customerData->id,
                'order_type'=>1,
                'product_offering_id'=>$productInformation->id,
                'bill_date'=>$formatNow,
                'installment_count'=>1,
                'purchase_ymd'=>$transfer_date,
                'purchase_amount'=>$principal,
            ]);
            
            $installment_create = installments::create([
                'customer_id'=>$customerData->id,
                'order_id'=>$drawdown_input_create->id,
                'installment_number'=>1,
                'due_ymd'=>$due_date,
                'principal'=>$principal,
                'interest'=>$interest,
            ]);
            
            $installment_history_create = installmentHistory::create([
                'customer_id'=>$customerData->id,
                'order_id'=>$drawdown_input_create->id,
                'installment_id'=>$installment_create->id,
                'paid_principal'=>0.00,
                'paid_interest'=>0.00,
                'paid_late_charge'=>0.00,
            ]);
            DB::commit();

            //$sendStatament = $this->sendDrawdownInputStatament($drawdown_id);
            // return $sendStatament;
            
            // if($drawdown_id){
            //     event(new ReceiveAmountConfirm($record));
            //     session(['success'=>true]);
            // }

            return response([
                'message' => "Drawdown input Tax ID : ".$tax_id." successfully",
                'status' => "success"
            ], 200);
        } catch(\Exception $exp) {
            DB::rollBack();
            return response([
                'message' => $exp->getMessage(),
                'status' => 'failed'
            ], 400);
        }
        
    }

    public function sendDrawdownInputStatament($drawdown_id){
        // return $drawdown_id;
        // $drawdown_data =;
        
        if($drawdown_id){
            event(new ReceiveAmountConfirm($drawdown_id));
            session(['success'=>true]);
        }
    }

    //Route::post('/outstanding_calculation',[InternalApiController::class,'outstandingCalculation']);
    public function outstandingCalculation(Request $request){
        $order_id = $request->order_id;
        $get_repayment_date = ($request->repayment_date==null)?Carbon::now()->format('d/m/Y'):$request->repayment_date;
        $repayment_date = Carbon::createFromFormat('d/m/Y',$get_repayment_date)->format('Y-m-d');
        $receive_amount = ($request->receive_amount==null)?0.00:str_replace(',','',$request->receive_amount);
        
        // $orderData = order::leftJoin('customers','customers.id','orders.customer_id')
        //                 ->leftJoin('installments','installments.order_id','orders.id')
        //                 ->leftJoin('installment_histories','installment_histories.order_id','orders.id')
        //                 ->leftJoin('product_offerings','product_offerings.id','orders.product_offering_id')
        //                 ->leftJoin('products','products.id','product_offerings.product_id')
        //                 ->where('orders.id', $order_id)
        //                 ->get()->first();
        $orderData = order::find($order_id);

        // $partialPaid = ReceiveAmountHistory::leftJoin('receive_amount_details','receive_amount_details.receive_amount_history_id','receive_amount_histories.id')
        //                 ->where('receive_amount_histories.order_id',$order_id)
        //                 ->get()->last();
                        
        // $partialPaidAll = ReceiveAmountHistory::leftJoin('receive_amount_details','receive_amount_details.receive_amount_history_id','receive_amount_histories.id')
        //                     ->where('receive_amount_histories.order_id',$order_id)
        //                     ->get();

        // $partialPaid = $partialPaidAll->last();
        
        // $partialPaidAllMapLatest = collect();

        // $orderData->paid_detail = $partialPaidAll->map(function ($item, $key) use ($partialPaidAllMapLatest)  {
        //     $reItem = collect();
        //     $reItem->put('receive_ymd', Carbon::createFromFormat('Y-m-d', $item->receive_ymd)->format('d F Y'));
        //     $reItem->put('receive_amount', $item->receive_amount);
        //     if($key>0){
        //         $reItem->put('this_paid_principal', ($item->paid_principal - $partialPaidAllMapLatest['paid_principal']));
        //         $reItem->put('this_paid_interest', ($item->paid_interest - $partialPaidAllMapLatest['paid_interest']));
        //         $reItem->put('this_paid_late_charge', ($item->paid_late_charge - $partialPaidAllMapLatest['paid_late_charge']));
        //     }else{
        //         $reItem->put('this_paid_principal', $item->paid_principal);
        //         $reItem->put('this_paid_interest', $item->paid_interest);
        //         $reItem->put('this_paid_late_charge', $item->paid_late_charge);
        //     }
            
        //     $partialPaidAllMapLatest->put('receive_ymd', $item->receive_ymd);
        //     $partialPaidAllMapLatest->put('receive_amount', $item->receive_amount);
        //     $partialPaidAllMapLatest->put('paid_principal', $item->paid_principal);
        //     $partialPaidAllMapLatest->put('paid_interest', $item->paid_interest);
        //     $partialPaidAllMapLatest->put('paid_late_charge', $item->paid_late_charge);
        //     return $reItem;
        // });

        // $orderData->all_principal = collect();
        // $orderData->all_interest = collect();
        // $orderData->all_delaypenalty = collect();
        // $orderData->all_total = collect();

        // if($partialPaid){
        //     $orderData->all_principal->put('paid', $partialPaid->paid_principal);
        //     $orderData->all_interest->put('paid', $partialPaid->paid_interest);
        //     $orderData->all_delaypenalty->put('paid', $partialPaid->paid_late_charge);
        //     $orderData['last_repayment_date'] = Carbon::createFromFormat('Y-m-d', $partialPaid->receive_ymd)->format('d F Y');
        // }else{
        //     $orderData->all_principal->put('paid', 0);
        //     $orderData->all_interest->put('paid', 0);
        //     $orderData->all_delaypenalty->put('paid', 0);
        //     $orderData['last_repayment_date'] = '-';
        // }

        // $orderData->all_principal->put('billing', $orderData->principal);
        // $orderData->all_principal->put('outstanding', ($orderData->all_principal['billing'] - $orderData->all_principal['paid']));
        
        // $orderData->all_interest->put('billing', $orderData->installments->delayPenaltyWithDate($repayment_date)['outstanding_interest']);
        // $orderData->all_interest->put('outstanding', $orderData->all_interest['billing'] - $orderData->all_interest['paid']);

        // // return $orderData;
        // // return $orderData->installments->delayPenaltyWithDate($repayment_date);
        // $orderData->all_delaypenalty->put('billing', $orderData->installments->delayPenaltyWithDate($repayment_date)['delay_penalty']);
        // $orderData->all_delaypenalty->put('outstanding', $orderData->all_delaypenalty['billing'] - $orderData->all_delaypenalty['paid']);
        
        // $orderData->all_total->put('billing', $orderData->all_principal['billing'] + $orderData->all_interest['billing'] + $orderData->all_delaypenalty['billing']);
        // $orderData->all_total->put('paid', $orderData->all_principal['paid'] + $orderData->all_interest['paid'] + $orderData->all_delaypenalty['paid']);
        // $orderData->all_total->put('outstanding', $orderData->all_principal['outstanding'] + $orderData->all_interest['outstanding'] + $orderData->all_delaypenalty['outstanding']);
        
        // if($receive_amount>0.00){
        //     $orderData->all_total->put('receive', round($receive_amount,2));
        //     $orderData->all_total->put('balance', round(($orderData->all_total['outstanding'] - $receive_amount),2));

        //     $receive_balance = $receive_amount;
        //     $last_receive_balance = $receive_amount;
        //     if($last_receive_balance > 0.00 && $orderData->all_delaypenalty['outstanding'] > 0.00){
        //         if($last_receive_balance >= $orderData->all_delaypenalty['outstanding']){
        //             $orderData->all_delaypenalty->put('receive', round($orderData->all_delaypenalty['outstanding'],2));
        //         }else{
        //             $orderData->all_delaypenalty->put('receive', round($last_receive_balance,2));
        //         }
        //         $orderData->all_delaypenalty->put('balance', round(($orderData->all_delaypenalty['outstanding'] - $orderData->all_delaypenalty['receive']),2));
        //         $last_receive_balance = round(($last_receive_balance - $orderData->all_delaypenalty['receive']),2);
        //     }else{
        //         $orderData->all_delaypenalty->put('receive', 0.00);
        //         $orderData->all_delaypenalty->put('balance', round(($orderData->all_delaypenalty['outstanding']),2));
        //     }
            
        //     if($last_receive_balance > 0.00 && $orderData->all_interest['outstanding'] > 0.00){
        //         if($last_receive_balance >= $orderData->all_interest['outstanding']){
        //             $orderData->all_interest->put('receive', round($orderData->all_interest['outstanding'],2));
        //         }else{
        //             $orderData->all_interest->put('receive', round($last_receive_balance,2));
        //         }
        //         $orderData->all_interest->put('balance', round(($orderData->all_interest['outstanding'] - $orderData->all_interest['receive']),2));
        //         $last_receive_balance = round(($last_receive_balance - $orderData->all_interest['receive']),2);
        //     }else{
        //         $orderData->all_interest->put('receive', 0.00);
        //         // $orderData->all_interest->put('balance', 0.00);
        //         $orderData->all_interest->put('balance', round(($orderData->all_interest['outstanding']),2));
        //     }
            
        //     if($last_receive_balance > 0.00 && $orderData->all_principal['outstanding'] > 0.00){
        //         if($last_receive_balance >= $orderData->all_principal['outstanding']){
        //             $orderData->all_principal->put('receive', round($orderData->all_principal['outstanding'],2));
        //         }else{
        //             $orderData->all_principal->put('receive', round($last_receive_balance,2));
        //         }
        //         $orderData->all_principal->put('balance', $orderData->all_principal['outstanding'] - $orderData->all_principal['receive']);
        //         $last_receive_balance = round(($last_receive_balance - $orderData->all_principal['receive']),2);
        //     }else{
        //         $orderData->all_principal->put('receive', 0.00);
        //         // $orderData->all_principal->put('balance', 0.00);
        //         $orderData->all_principal->put('balance', round(($orderData->all_principal['outstanding']),2));
        //     }
        //     $orderData['last_receive_balance'] = $last_receive_balance;
        // }else{
        //     $orderData->all_principal->put('receive', 0.00);
        //     $orderData->all_interest->put('receive', 0.00);
        //     $orderData->all_delaypenalty->put('receive', 0.00);
        //     $orderData->all_total->put('receive', 0.00);
        //     $orderData['last_receive_balance'] = 0.00;

        //     $orderData->all_principal->put('balance', $orderData->all_principal['outstanding']);
        //     $orderData->all_interest->put('balance', $orderData->all_interest['outstanding']);
        //     $orderData->all_delaypenalty->put('balance', $orderData->all_delaypenalty['outstanding']);
        //     $orderData->all_total->put('balance', $orderData->all_total['outstanding']);
        // }
        // if($orderData->installments->delayPenaltyWithDate($repayment_date)['date_diff_from_due'] > 0){
        //     $orderData['daypassdue'] = $orderData->installments->delayPenaltyWithDate($repayment_date)['date_diff_from_due'];
        // }else{
        //     $orderData['daypassdue'] = 0;
        // }
        return $orderData->installments->first()->allocateReceiveAmount($repayment_date,$receive_amount);
    }

    //Route::post('/repayment_submit',[InternalApiController::class,'repaymentSubmit'])
    public function repaymentSubmit(Request $request){
        //return $request;
        $order_id = $request->order_id;
        $get_repayment_date = ($request->repayment_date==null) ? Carbon::now()->format('d/m/Y') : $request->repayment_date;
        $repayment_date = Carbon::createFromFormat('d/m/Y',$get_repayment_date)->format('Y-m-d');
        $receive_amount = ($request->receive_amount==null) ? 0.00 : str_replace(',', '',$request->receive_amount);
        
        $outstandingCal = $this->outstandingCalculation($request);

        $record = ReceiveAmountHistory::create([
            'order_id'=>$order_id,
            'comment'=>$request->receive_comment,
            'receive_ymd'=>$repayment_date,
            // 'paid_up_ymd'=>$paid_up_ymd,
            'receive_amount'=>$receive_amount,
            'create_user_id'=>auth()->user()->id,
            'net_pay_amount'=>$receive_amount,
        ]);

        $receive_amount_history_id = $record->id;

        $paid_principal = $outstandingCal['allocate_principal'];
        $paid_interest = $outstandingCal['allocate_interest'];
        $paid_late_charge = $outstandingCal['allocate_delay_penalty'];

        $installment = installments::where('order_id', $order_id)->first();

        // return $installment;
        // dd($installment);

        $installment->paid_principal += $paid_principal;
        $installment->paid_interest += $paid_interest;
        $installment->paid_late_charge += $paid_late_charge;
        if($outstandingCal['balance_principal'] == 0){
            $installment->paid_up_ymd = Carbon::createFromFormat('d/m/Y',$get_repayment_date)->isoFormat('YYYYMMDD');
            $order = order::find($order_id);
            $order->paid_up_ymd = Carbon::createFromFormat('d/m/Y',$get_repayment_date)->isoFormat('YYYYMMDD');
            $order->updated_at = Carbon::now();
            $order->save();
        }
            
        $installment->save();
        //receive amount detail can call via event
        //$order = order::find(request()->order_id);
        $detail = ReceiveAmountDetail::create([
            'receive_amount_history_id'=>$record->id,
            'dealer_type',
            'installment_number',
            'switched_date',
            'rescheduled_date',
            'repayment_ymd'=>$request->receive_date,
            'principal'=>$outstandingCal['billing_principal'],
            'interest'=>$outstandingCal['daily_interest'],
            'late_charge'=>$outstandingCal['delay_penalty'],
            'paid_principal'=>$paid_principal,
            'paid_interest'=>$paid_interest,
            'paid_late_charge'=>$paid_late_charge,
            'total_principal'=>$outstandingCal['balance_principal'],
            'total_interest'=>$outstandingCal['balance_interest'],
            'total_late_charge'=>$outstandingCal['balance_delay_penalty'],
            // 'waive_late_charge'=>$exempt_late_charge,
            // 'waive_interest'=>$exempt_interest,
            'contractor_id',
            'payment_id',
            'order_id'=>$order_id,
            'installment_id'=>$installment->id,
            'dealer_id',
            // 'exceeded_occurred_amount'=>$request->exceeded_amount,
            // 'payback_amount'=>$request->payback_amount_to_supplier,
            'outstanding_balance'=>$outstandingCal['balance_total'],
            // 'tax'=>$request->tax,
            // 'paid_tax'=>$request->tax_to_pay,
        ]);

        if($record){
            return $record;
            // event(new ReceiveAmountConfirm($record));
            // session(['success'=>true]);
        }

        return $detail;
    }
}
