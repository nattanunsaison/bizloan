<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\order;
use App\Models\order_detail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
//require_once '..\vendor\autoload.php';
//use PDF;
use App\Models\main_summary;
use App\Models\receive_amount_histories;
use App\Models\repayment_summaries;
use App\Models\contractors;
use App\Models\daily_summaries;
use App\Models\payments;
use App\Models\exemption_late_charges;
use App\Models\installments;
use Maatwebsite\Excel\Concerns\FromView;
use App\Exports\FromViewExport;
use Illuminate\Support\Facades\Mail;
use App\Models\eligibilities;
use App\Exports\{PaymentVoucherExport,ScbTemplateExport,BuyerReceiptExport,SellerReceiptExport};
use Illuminate\Support\Str;
use App\Models\{dealers,areas,dealer_type_settings,DealerMonthlyInput,ReceiptRecord,ScfReceiveAmountHistory};
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    //Route::get('/export/dealer_statement',[ReportController::class,'exportDealerStatement']);
    public function exportDealerStatement($record){
        $repayment_date = $record->receive_ymd;
        //return $record->order->installments->first()->delayPenaltyWithDate($repayment_date);
        //return $record->order->installments->first();
        $formatNow = Carbon::now()->isoFormat('YYYYMMDD-HHmm');
        //$file_name = "dealer_statement-$record->dealer_id-$record->input_ymd-$record->remarks.xlsx";
        //return Excel::store(new DealerStatementExport($order_with_fee_rates), $file_name,"dealer_statement");
        //return $orders;
        return view('reports.dealer_statement', [
            'record' => $record,
        ]);
        
    }   

    //Route::get('/payback_detail',[OrderController::class,'paybackDetail']);
    public function paybackDetail(){
        $receive_history_id = request()->id;
        $record = ScfReceiveAmountHistory::find($receive_history_id);
        return $this->exportDealerStatement($record);
    }

    //Route::get('/download/dealer_statement',[ReportController::class,'downloadDealerStatement']);
    public function downloadDealerStatement(){
        $receive_history_id = request()->id;
        $record = ScfReceiveAmountHistory::find($receive_history_id);
        $suffix = $record->order->dealer->dealer_name.'-input-'.$record->order->input_ymd;
        $file_name = "supplier_payback_statement-$suffix.xlsx";
        return (new DealerStatementExport($record))->download($file_name);
    }


    //Route::get('/dealer_payment/scb_template',[ReportController::class,'dealerPaymentSCBTemplate']);
    public function dealerPaymentSCBTemplate(Request $request){
        $receive_history_id = $request->id;
        $record = ScfReceiveAmountHistory::find($receive_history_id);

        //return $record->order->dealer->scb_account;
        
        $suffix = $record->order->dealer->dealer_name.'-input-'.$record->order->input_ymd;
        $file_name = "supplier_payback_scb_template-$suffix.xlsx";

        return Excel::download(new ScbTemplateExport($receive_history_id), $file_name);
        
        return view('account.scb_payment_template',[
            'record'=>$record,
        ]);
    }

    //Route::get('/buyer_receipt',[ReportController::class,'buyerReceipt']);
    public function buyerReceipt(Request $request){
        $receive_history_id = $request->id;
        $record = ScfReceiveAmountHistory::find($receive_history_id);

        //return $record->order->dealer->scb_account;
        
        $suffix = $record->order->dealer->dealer_name.'-input-'.$record->order->input_ymd;
        $file_name = "buyer_receipt-$suffix.xlsx";

        //return Excel::download(new ScbTemplateExport($receive_history_id), $file_name);
        $amount_read = $this->Convert($record->receive_amount);
        return view('reports.buyer_receipt',[
            'record'=>$record,
            'amount_read'=>$amount_read
        ]);
    }

    //Route::get('/download/buyer_receipt',[ReportController::class,'downloadBuyerReceipt']);
    public function downloadBuyerReceipt(){
        $receive_history_id = request()->id;
        $record = ScfReceiveAmountHistory::find($receive_history_id);
        $suffix = $record->order->dealer->dealer_name.'-input-'.$record->order->input_ymd;
        $file_name = "buyer_receipt-$suffix.xlsx";
        return (new BuyerReceiptExport($record))->download($file_name);
    }


    //Route::get('/payment_voucher',[ReportController::class,'paymentVoucher']);
    public function paymentVoucher(Request $request){
        $receive_history_id = $request->id;
        $record = ScfReceiveAmountHistory::find($receive_history_id);

        //return $record->order->dealer->scb_account;
        
        $suffix = $record->order->dealer->dealer_name.'-input-'.$record->order->input_ymd;
        $file_name = "payment_voucher-$suffix.xlsx";

        return view('account.payment_voucher',[
            'record'=>$record,
        ]);
    }

    //Route::get('/download/payment_voucher',[ReportController::class,'downloadPaymentVoucher']);
    public function downloadPaymentVoucher(){
        $receive_history_id = request()->id;
        $record = ScfReceiveAmountHistory::find($receive_history_id);
        $suffix = $record->order->dealer->dealer_name.'-input-'.$record->order->input_ymd;
        $file_name = "payment_voucher-$suffix.xlsx";
        return (new PaymentVoucherExport($record))->download($file_name);
    }

    //Route::get('/seller_receipt',[ReportController::class,'sellerReceipt']);
    public function sellerReceipt(Request $request){
        $receive_history_id = $request->id;
        $record = ScfReceiveAmountHistory::find($receive_history_id);

        //return $record->order->dealer->scb_account;
        
        $suffix = $record->order->dealer->dealer_name.'-input-'.$record->order->input_ymd;
        $file_name = "seller_receipt-$suffix.xlsx";

        //return Excel::download(new ScbTemplateExport($receive_history_id), $file_name);
        $receive_amount_detail = $record->receive_amount_detail;
        $amount_read = $this->Convert($receive_amount_detail->principal+$receive_amount_detail->paid_interest+$receive_amount_detail->paid_late_charge);
        return view('account.seller_receipt',[
            'record'=>$record,
            'amount_read'=>$amount_read
        ]);
    }

    //Route::get('/download/seller_receipt',[ReportController::class,'downloadSellerReceipt']);
    public function downloadSellerReceipt(){
        $receive_history_id = request()->id;
        $record = ScfReceiveAmountHistory::find($receive_history_id);
        $suffix = $record->order->dealer->dealer_name.'-input-'.$record->order->input_ymd;
        $file_name = "seller_receipt-$suffix.xlsx";
        return (new SellerReceiptExport($record))->download($file_name);
    }

    function Convert($amount_number){
        $amount_number = number_format($amount_number, 2, ".","");
        $pt = strpos($amount_number , ".");
        $number = $fraction = "";
        if ($pt === false) 
            $number = $amount_number;
        else
        {
            $number = substr($amount_number, 0, $pt);
            $fraction = substr($amount_number, $pt + 1);
        }
        
        $ret = "";
        $baht = $this->ReadNumber ($number);
        if ($baht != "")
            $ret .= $baht . "บาท";
        
        $satang = $this->ReadNumber($fraction);
        if ($satang != "")
            $ret .=  $satang . "สตางค์";
        else 
            $ret .= "ถ้วน";
        return $ret;
    }

    function ReadNumber($number){
        $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
        $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
        $number = $number + 0;
        $ret = "";
        if ($number == 0) return $ret;
        if ($number > 1000000)
        {
            $ret .= $this->ReadNumber(intval($number / 1000000)) . "ล้าน";
            $number = intval(fmod($number, 1000000));
        }
        
        $divider = 100000;
        $pos = 0;
        while($number > 0)
        {
            $d = intval($number / $divider);
            $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : 
                ((($divider == 10) && ($d == 1)) ? "" :
                ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
            $ret .= ($d ? $position_call[$pos] : "");
            $number = $number % $divider;
            $divider = $divider / 10;
            $pos++;
        }
        return $ret;
    }

}
