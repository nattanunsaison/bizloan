<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\{order,contractors,Customer,dealers,ReceiveAmountHistory,ReceiveAmountDetail,Product,ProductOffering};
use Illuminate\View\View;
use App\Enum\FlagText;
use App\Exports\DrawdownStatementExport;

class BusinessLoanController extends Controller
{
    // Route::get('/business_loan/customers/register',[BusinessLoanController::class,'businessLoanConRegis'])->name('business_loan.customers.register');
    public function businessLoanConRegis(){
        $customers = contractors::where('status', 1)
                    ->where('contractor_type', 2)
                    ->select('id', 'tax_id', 'th_company_name', 'en_company_name')
                    ->get();
        
        return view('busloan_customers_register',[
            'customers'=>$customers
        ]);
    }

    // Route::get('/business_loan/customers',[BusinessLoanController::class,'businessLoanCon'])->name('business_loan.customers');
    public function businessLoanCon(){
        $customers = Customer::leftJoin('users','users.id','customers.kyc_pic')
                    ->where('customers.status', 1)
                    ->select('customers.*', 'users.name AS username')
                    ->get();

        $customer_list = $customers->map(function( $item ){
            $item->status_text = FlagText::tryFrom((int)$item->status)?->status();
            $item->master_agreement_text = FlagText::tryFrom((int)$item->master_agreement)?->master_agreement();
            $item->ready_status_text = FlagText::tryFrom((int)$item->ready_status)?->ready_status();
            return $item;
        });
        
        return view('busloan_customers',[
            'customers'=>$customer_list
        ]);
    }

    //Route::get('/business_loan/summary',[BusinessLoanController::class,'businessLoanSummary'])->name('business_loan.summary');
    public function businessLoanSummary(){
        $orders = order::query()
                    ->where('orders.deleted', 0)
                    ->get();
        //return $orders->first()->installments->first()->calAccruInterestAndDelayPenalty(\Carbon\Carbon::now()->isoFormat('YYYY-MM-DD'));
        // dd($orders);
        return view('busloan_summary',[
            'orders'=>$orders
        ]);
    }

    // Route::get('/business_loan/drawdown/input',[BusinessLoanController::class,'businessLoanDrawdownInput'])->name('business_loan.drawdown.input');
    public function businessLoanDrawdownInput(Request $request){
        // return $request;
        $conid = $request->conid;

        
        $customerData = Customer::where('status', 1)
                        ->where('id', $conid)
                        ->get()->first();
                    
        // return ($customerData->id);
        $busloan_product = ProductOffering::leftJoin('products','product_offerings.product_id','products.id')
                            ->where('product_offerings.offering_grade', '=', $customerData->offering_grade)
                            ->select(
                                'product_offerings.id', 'product_offerings.offering_grade',
                                'product_offerings.interest_rate', 'product_offerings.delay_penalty_rate', 'product_offerings.discount_rate',
                                'products.product_code','products.product_name','products.terms','products.loan_amount'
                            )
                            ->get();

        // return $busloan_product;
        return view('busloan_drawdown_input',[
            'customer_data'=>$customerData,
            'products'=>$busloan_product
        ]);
    }
    
    // Route::get('/download/drawdown_statement',[BusinessLoanController::class,'downaloadDrawdownStatement'])->name('business_loan.summary');
    public function downaloadDrawdownStatement(Request $request){
        $order_id = $request->id;
        
        $orderData = order::leftJoin('customers','customers.id','orders.customer_id')
                        ->leftJoin('installments','installments.order_id','orders.id')
                        ->leftJoin('installment_histories','installment_histories.order_id','orders.id')
                        ->leftJoin('product_offerings','product_offerings.id','orders.product_offering_id')
                        ->leftJoin('products','products.id','product_offerings.product_id')
                        ->where('orders.id', $order_id)
                        ->get()->last();

                        
        // return ($orderData);
        // return ($orderData->installments->first()->delayPenalty());
        
        $orderData->due_ymd_text = Carbon::createFromFormat('Ymd',$orderData->due_ymd)->addYears(543)->locale('th-TH')->isoFormat('LL');
        $orderData->purchase_ymd_text = Carbon::createFromFormat('Ymd',$orderData->purchase_ymd)->addYears(543)->locale('th-TH')->isoFormat('LL');
        $orderData->bill_principal = $orderData->principal - $orderData->paid_principal;
        $orderData->bill_interest = $orderData->interest - $orderData->paid_interest;
        $orderData->bill_total = $orderData->bill_principal + $orderData->bill_interest;
        // return ($orderData);

        
        // $receive_history_id = request()->id;
        // $record = ScfReceiveAmountHistory::find($receive_history_id);
        $suffix = $orderData->order_number;
        $file_name = "drawdown_statement-$suffix.xlsx";
        //return public_path('\images\saison_credit.png');
        //return (new DrawdownStatementExport($orderData))->download($file_name);
        $file_name = "drawdown_statement-$suffix.pdf";
        //create pdf
        $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_header' => '3',
                'margin_top' => '10',
                'margin_bottom' => '20',
                'margin_footer' => '2',
            ]);
        $mpdf->useDictionaryLBR = false;
        $file_path = storage_path('/app/public/statement')."/$file_name";
        $mpdf->WriteHTML(view('reports.business_loan_statement_export_print', [
            'order' => $orderData,
        ]));
        $mpdf->Output($file_path,'F');
        ///return;
        //return (new DrawdownStatementExport($orderData))->store($file_name,'public', \Maatwebsite\Excel\Excel::MPDF,);

        return view('reports.business_loan_statement_export_print', [
            'order' => $orderData,
        ]);
    }

    // Route::get('/business_loan/repayment',[BusinessLoanController::class,'businessLoanRepayment'])->name('business_loan.repayment');
    public function businessLoanRepayment(Request $request){
        // return $request;
        $order_id = $request->order_id;
        $repayment_date = $request->repayment_date;
        
        /* $orderData = order::query()
                        ->leftJoin('customers','customers.id','orders.customer_id')
                        ->leftJoin('installments','installments.order_id','orders.id')
                        ->leftJoin('installment_histories','installment_histories.order_id','orders.id')
                        ->leftJoin('product_offerings','product_offerings.id','orders.product_offering_id')
                        ->leftJoin('products','products.id','product_offerings.product_id')
                        ->where('orders.id', $order_id)
                        ->get()->first(); */
        $orderData = order::find($order_id);
        $histories = $orderData->receive_histories;
        $first_payment = $histories->first();
        $last_receive_date = null;
        //return $orderData->installments->first()->order->product_offering->product;//->allocateReceiveAmount(\Carbon\Carbon::parse('20240524')->isoFormat('YYYY-MM-DD'),20000);
        //return $orderData->installments->first()->calAccruInterestAndDelayPenalty(\Carbon\Carbon::parse('20241030')->isoFormat('YYYY-MM-DD'));//interestWithDate(\Carbon\Carbon::now()->isoFormat('YYYY-MM-DD'));

        //return;
        if(!$orderData){
            dd('no data');
        }
        //return $orderData;
        $purchase_ymd = $orderData->purchase_ymd;
        $due_ymd = $orderData->due_ymd;
        $from_ymd = $orderData->from_ymd;

        $partialPaidAll = ReceiveAmountHistory::leftJoin('receive_amount_details','receive_amount_details.receive_amount_history_id','receive_amount_histories.id')
                        ->where('receive_amount_histories.order_id',$order_id)
                        ->get();
        
        $partialPaid = $partialPaidAll->last();

        $partialPaidAllMapLatest = collect();
        $orderData->paid_detail = $partialPaidAll->map(function ($item, $key) use ($partialPaidAllMapLatest)  {
            $reItem = collect();
            $reItem->put('receive_ymd', Carbon::createFromFormat('Y-m-d', $item->receive_ymd)->format('d F Y'));
            $reItem->put('receive_amount', $item->receive_amount);
            if($key>0){
                $reItem->put('this_paid_principal', ($item->paid_principal - $partialPaidAllMapLatest['paid_principal']));
                $reItem->put('this_paid_interest', ($item->paid_interest - $partialPaidAllMapLatest['paid_interest']));
                $reItem->put('this_paid_late_charge', ($item->paid_late_charge - $partialPaidAllMapLatest['paid_late_charge']));
            }else{
                $reItem->put('this_paid_principal', $item->paid_principal);
                $reItem->put('this_paid_interest', $item->paid_interest);
                $reItem->put('this_paid_late_charge', $item->paid_late_charge);
            }
            
            $partialPaidAllMapLatest->put('receive_ymd', $item->receive_ymd);
            $partialPaidAllMapLatest->put('receive_amount', $item->receive_amount);
            $partialPaidAllMapLatest->put('paid_principal', $item->paid_principal);
            $partialPaidAllMapLatest->put('paid_interest', $item->paid_interest);
            $partialPaidAllMapLatest->put('paid_late_charge', $item->paid_late_charge);
            return $reItem;
        });
        // dd($partialPaidAllMap);

        $orderData->all_principal = collect();
        $orderData->all_interest = collect();
        $orderData->all_delaypenalty = collect();
        $orderData->all_total = collect();
        // $orderData->paid_detail = collect();

        if($partialPaid){
            // $orderData->all_tax->put('paid', $partialPaid->sum('paid_tax'));
            $orderData->all_principal->put('paid', $partialPaid->paid_principal);
            $orderData->all_interest->put('paid', $partialPaid->paid_interest);
            $orderData->all_delaypenalty->put('paid', $partialPaid->paid_late_charge);
            // return $partialPaid->receive_ymd;
            $orderData['last_repayment_date'] = Carbon::createFromFormat('Y-m-d', $partialPaid->receive_ymd)->format('d F Y');
        }else{
            $orderData->all_principal->put('paid', 0);
            $orderData->all_interest->put('paid', 0);
            $orderData->all_delaypenalty->put('paid', 0);
            $orderData['last_repayment_date'] = '-';
            // $orderData->all_tax->put('paid', 0);
        }

        $orderData->all_principal->put('billing', $orderData->principal);

        $orderData->all_principal->put('outstanding', ($orderData->all_principal['billing'] - $orderData->all_principal['paid']));

        // $orderData->all_interest->put('billing', $orderData->interest);
        $orderData->all_interest->put('billing', $orderData->installments->first()->delayPenalty()['outstanding_interest']);
        $orderData->all_interest->put('outstanding', $orderData->all_interest['billing'] - $orderData->all_interest['paid']);
        
        $orderData->all_delaypenalty->put('billing', $orderData->installments->first()->delayPenalty()['delay_penalty']);
        $orderData->all_delaypenalty->put('outstanding', $orderData->all_delaypenalty['billing'] - $orderData->all_delaypenalty['paid']);
        
        $orderData->all_total->put('billing', $orderData->all_principal['billing'] + $orderData->all_interest['billing'] + $orderData->all_delaypenalty['billing']);
        $orderData->all_total->put('paid', $orderData->all_principal['paid'] + $orderData->all_interest['paid'] + $orderData->all_delaypenalty['paid']);
        $orderData->all_total->put('outstanding', $orderData->all_principal['outstanding'] + $orderData->all_interest['outstanding'] + $orderData->all_delaypenalty['outstanding']);

        if($orderData->installments->first()->delayPenalty()['date_diff_from_last_due'] > 0){
            $orderData['daypassdue'] = $orderData->installments->first()->delayPenalty()['date_diff_from_last_due'];
        }else{
            $orderData['daypassdue'] = 0;
        }

        //return $orderData;

        return view('busloan_repayment',[
            'order'=>$orderData
        ]);
    }

    //Route::get('/reset_repayment_info',[BusinessLoanController::class,'resetRepaymentInfo']);
    public function resetRepaymentInfo(){
        $order = order::find(request()->order_id);
        $order->paid_up_ymd = null;
        $order->save();
        $installments = $order->installments->each(function($installment){
            $installment->paid_principal = 0;
            $installment->paid_interest = 0;
            $installment->paid_late_charge = 0;
            $installment->paid_up_ymd = null;
            $installment->save();
        });
        //return $order->receive_histories->first()->receive_amount_detail;
        $receive_histories = $order->receive_histories->each(function($history){
            $now = Carbon::now();
            $detail = $history->receive_amount_detail;
            echo "Detail id: $detail?->id<br>";
            if(!is_null($detail)){
                $detail->deleted_at = $now;
                $detail->updated_at = $now;
                $detail->save();
            }
            $history->deleted_at = $now;
            $history->updated_at = $now;
            $history->save();
        }); 
        //update installment paid info
    }
}