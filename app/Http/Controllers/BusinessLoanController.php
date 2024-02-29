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
use App\Http\Controllers\HelperController;

class BusinessLoanController extends Controller
{
    // Route::get('/business_loan/customers/register',[BusinessLoanController::class,'businessLoanConRegis'])->name('business_loan.customers.register');
    public function businessLoanConRegis(){
        $customers = contractors::query()
                    ->where('status', 1)
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
                    ->orderBy('id','desc')
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

        
        $customerData = Customer::query()
                        //->where('status', 1)
                        ->where('id', $conid)
                        ->get()->first();
                    
        // return ($customerData->id);
        $busloan_product = Product::query()
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
        
        $orderData = order::find($order_id);

                        
        // return ($orderData);
        // return ($orderData->installments->first()->delayPenalty());
        
        $orderData->due_ymd_text = (new HelperController)->dateThai($orderData->installments->first()->due_ymd);
        $orderData->purchase_ymd_text = (new HelperController)->dateThai($orderData->purchase_ymd);
        $installment = $orderData->installments->first();
        $orderData->bill_principal = $installment->principal - $installment->paid_principal;
        $orderData->bill_interest = $installment->interest - $installment->paid_interest;
        $orderData->bill_total = $installment->bill_principal + $installment->bill_interest;
        //return ($orderData);

        
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
        //return;
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

    //Route::get('/create_agreement', [BusinessLoanController::class, 'createAgreement']);
    public function createAgreement_final(){
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontSize(15);
        $phpWord->setDefaultFontName('Browallia New');
        $section = $phpWord->addSection();
        $section->addImage("./images/saison_credit.png",    
        array(
            'height'        => 50,
            'marginTop'     => -1,
            'marginLeft'    => -1,
            'wrappingStyle' => 'behind'
        )); 
        $text = $section->addText('สัญญาข้อกำหนดและเงื่อนไขเงินกู้',['bold'=>true,'size'=>20,'name'=>'Browallia New'],['alignment'=>\PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $normal_font_style = ['size'=>15,'name'=>'Browallia New'];
        $section->addText('สัญญาข้อกำหนดและเงื่อนไขเงินกู้ฉบับนี้ (“สัญญาฯ”) ทำขึ้นในวันที่ 22 ธันวาคม 2566 โดยและระหว่าง ',$normal_font_style);
        $phpWord->addNumberingStyle(
            'multilevel',
            array(
                'type' => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                    array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
                )
            )
        );
        $section->addListItem('บริษัท สยาม เซย์ซอน จำกัด บริษัทจัดตั้งขึ้นภายใต้กฎหมายของประเทศไทย โดยมีสำนักงานใหญ่ตามทะเบียนตั้งอยู่ที่ เลขที่ 1 ถนนปูนซิเมนต์ไทย แขวงบางซื่อ เขตบางซื่อ กรุงเทพมหานคร (ต่อไปในสัญญาฯ เรียกว่า “บริษัทฯ” ) และ ', 0, null, 'multilevel');
        $section->addListItem('List Item I.a', 1, null, 'multilevel');
        $section->addListItem('List Item I.b', 1, null, 'multilevel');
        $section->addListItem('บริษัท เมทัล คอปเปอร์ จำกัด บริษัทจัดตั้งขึ้นภายใต้กฎหมายของประเทศไทย โดยมีสำนักงานใหญ่ เลขที่ 666 ถนนสุวินทวงศ์ แขวงแสนแสบ เขตมีนบุรี กรุงเทพมหานคร (ต่อไปในสัญญาฯ เรียกว่า “ผู้กู้”) ', 0, null, 'multilevel');
        $section->addText('โดยที่  ',$normal_font_style);
        $section->addText('กู้ประสงค์จะกู้ยืมเงินจากบริษัทฯ และบริษัทฯ ประสงค์ที่จะให้ผู้กู้กู้ยืมเงิน โดยผู้กู้ตกลงที่จะชำระเงินกู้คืนทั้งหมดให้แก่บริษัทฯ ภายในระยะเวลาที่กำหนดไว้ ดังนั้น ผู้กู้และบริษัทฯ จึงประสงค์ที่จะเข้าทำสัญญาฯ ฉบับนี้เพื่อตกลงข้อกำหนดและเงื่อนไขในการกู้ยืมเงินอันมีสาระสำคัญดังต่อไปนี้  ',$normal_font_style);
        //$section2 = $phpWord->addSection();
        // $phpWord->addNumberingStyle(
        //     'multilevel2',
        //     array(
        //         'type' => 'multilevel',
        //         'levels' => array(
        //             array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
        //             array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
        //             array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720)
        //         )
        //     )
        // );
        $headingNumberingStyleName = 'multilevel2';
        $phpWord->addNumberingStyle(
            $headingNumberingStyleName,
            ['type' => 'multilevel',
                'levels' => [
                    ['pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1'],
                    ['pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2'],
                    ['pStyle' => 'Heading3', 'format' => 'decimal', 'text' => '%1.%2.%3'],
                ],
            ]
        );
        //1.	คำนิยาม
        $section->addListItem('คำนิยาม', 0, null, 'multilevel2');
        //1.1	คำต่อไปนี้ให้มีความหมายดังต่อไปนี้ เว้นแต่จะระบุไว้เป็นอย่างอื่น
        $section->addListItem('คำต่อไปนี้ให้มีความหมายดังต่อไปนี้ เว้นแต่จะระบุไว้เป็นอย่างอื่น', 1, null, 'multilevel2');
        //“เงินกู้” หมายถึง เงินที่บริษัทฯ ให้ผู้กู้ยืมภายใต้การกู้ในแต่ละครั้ง
        $section->addListItem('“เงินกู้” หมายถึง เงินที่บริษัทฯ ให้ผู้กู้ยืมภายใต้การกู้ในแต่ละครั้ง', 2, null, 'multilevel2');
        //“ดอกเบี้ยเงินกู้” หมายถึง ดอกเบี้ยของเงินกู้ในอัตราที่บริษัทฯ กำหนดสำหรับเงินกู้ภายใต้การเบิกใช้เงินกู้นั้น ๆ
        $section->addListItem('“ดอกเบี้ยเงินกู้” หมายถึง ดอกเบี้ยของเงินกู้ในอัตราที่บริษัทฯ กำหนดสำหรับเงินกู้ภายใต้การเบิกใช้เงินกู้นั้น ๆ', 2, null, 'multilevel2');
        //“วันเวลาทำการ” หมายถึง ช่วงเวลาเริ่มตั้งแต่ 8 นาฬิกา 30 นาที ถึง 17 นาฬิกา 30 นาที ของทุกวันจันทร์ถึงวันศุกร์ยกเว้นวันหยุดราชการและวันหยุดนักขัตฤกษ์
        $section->addListItem('“วันเวลาทำการ” หมายถึง ช่วงเวลาเริ่มตั้งแต่ 8 นาฬิกา 30 นาที ถึง 17 นาฬิกา 30 นาที ของทุกวันจันทร์ถึงวันศุกร์ยกเว้นวันหยุดราชการและวันหยุดนักขัตฤกษ์', 2, null, 'multilevel2');
        //“ค่าธรรมเนียม Roll Over” หมายถึง ค่าธรรมเนียมเงินกู้ที่บริษัทฯ เรียกเก็บจากผู้กู้ในกรณีที่ผู้กู้ประสงค์จะยกยอดเงินกู้คงค้างเดิมเพื่อไปทำสัญญาข้อกำหนดและเงื่อนไขเงินกู้ฉบับใหม่นอกเหนือจากสัญญาฯฉบับนี้ ทั้งนี้บริษัทฯ มีสิทธิที่จะอนุมัติหรือปฏิเสธความประสงค์ดังกล่าวของผู้กู้ได้ตามที่บริษัทฯเห็นสมควร
        $section->addListItem('“ค่าธรรมเนียม Roll Over” หมายถึง ค่าธรรมเนียมเงินกู้ที่บริษัทฯ เรียกเก็บจากผู้กู้ในกรณีที่ผู้กู้ประสงค์จะยกยอดเงินกู้คงค้างเดิมเพื่อไปทำสัญญาข้อกำหนดและเงื่อนไขเงินกู้ฉบับใหม่นอกเหนือจากสัญญาฯฉบับนี้ ทั้งนี้บริษัทฯ มีสิทธิที่จะอนุมัติหรือปฏิเสธความประสงค์ดังกล่าวของผู้กู้ได้ตามที่บริษัทฯเห็นสมควร', 2, null, 'multilevel2');
        //2.	ข้อตกลงทั่วไปเกี่ยวกับเงินกู้
        $section->addListItem('ข้อตกลงทั่วไปเกี่ยวกับเงินกู้', 0, null, 'multilevel2');
        //2.1.	บริษัทฯ ตกลงให้ผู้กู้ยืมเงินกู้ และผู้กู้ตกลงยืมเงินกู้จากบริษัทฯ เป็นจำนวนเงินที่ได้ระบุไว้ในเอกสารแนบท้ายสัญญาฯฉบับนี้ ทั้งนี้บริษัทฯ มีสิทธิที่จะอนุมัติหรือปฏิเสธการให้เงินกู้ของผู้กู้ ตามที่บริษัทฯ เห็นสมควรได้
        $section->addListItem('บริษัทฯ ตกลงให้ผู้กู้ยืมเงินกู้ และผู้กู้ตกลงยืมเงินกู้จากบริษัทฯ เป็นจำนวนเงินที่ได้ระบุไว้ในเอกสารแนบท้ายสัญญาฯฉบับนี้ ทั้งนี้บริษัทฯ มีสิทธิที่จะอนุมัติหรือปฏิเสธการให้เงินกู้ของผู้กู้ ตามที่บริษัทฯ เห็นสมควรได้', 1, null, 'multilevel2');
        //2.2.	บริษัทฯจะทำการโอนเงินกู้ให้แก่ผู้กู้ภายในวันตามที่ได้ระบุไว้ในเอกสารแนบท้ายสัญญาฯฉบับนี้
        $section->addListItem('บริษัทฯจะทำการโอนเงินกู้ให้แก่ผู้กู้ภายในวันตามที่ได้ระบุไว้ในเอกสารแนบท้ายสัญญาฯฉบับนี้', 1, null, 'multilevel2');
        //3.	ระยะเวลาชำระเงินกู้
        $section->addListItem('ระยะเวลาชำระเงินกู้', 0, null, 'multilevel2');
        //บริษัทฯ เป็นผู้กำหนดระยะเวลาในการชำระเงินกู้ และผู้กู้ตกลงที่จะชำระเงินกู้คืนทั้งหมดให้แก่บริษัทฯภายในระยะเวลาดังกล่าว รายละเอียดตามที่ระบุในเอกสารแนบท้ายสัญญาฯฉบับนี้
        $section->addListItem('บริษัทฯ เป็นผู้กำหนดระยะเวลาในการชำระเงินกู้ และผู้กู้ตกลงที่จะชำระเงินกู้คืนทั้งหมดให้แก่บริษัทฯภายในระยะเวลาดังกล่าว รายละเอียดตามที่ระบุในเอกสารแนบท้ายสัญญาฯฉบับนี้', 1, null, 'multilevel2');
        //4.	วิธีการโอนเงินกู้
        $section->addListItem('วิธีการโอนเงินกู้', 0, null, 'multilevel2');
        $message = 'ผู้กู้ตกลงให้บริษัทฯ ทำการโอนเงินผ่านระบบเข้าบัญชีและภายในกำหนดระยะเวลาที่ได้กำหนด โดยผู้กู้ขอรับรองและรับผิดชอบตามที่บริษัทฯ ได้ดำเนินการตามประสงค์ของผู้กู้ดังกล่าวข้างต้นทุกประการ ทั้งนี้ให้ถือว่าผู้กู้ได้รับเงินแล้วอย่างครบถ้วนตามรายละเอียดบัญชี จำนวนและวันที่ได้ระบุในเอกสารแนบท้ายสัญญาฯฉบับนี้';
        $section->addListItem('message', 1, null, 'multilevel2');
       
        //5.	ดอกเบี้ยเงินกู้
        $section->addListItem('ดอกเบี้ยเงินกู้', 0, null, 'multilevel2');
        //5.1.	ผู้กู้ตกลงชำระดอกเบี้ยเงินกู้ตามอัตราที่บริษัทฯ กำหนดไว้ในเอกสารแนบท้ายสัญญาฯฉบับนี้
        $section->addListItem('ผู้กู้ตกลงชำระดอกเบี้ยเงินกู้ตามอัตราที่บริษัทฯ กำหนดไว้ในเอกสารแนบท้ายสัญญาฯฉบับนี้', 1, null, 'multilevel2');  
        //5.2.	การคำนวณดอกเบี้ยเงินกู้ให้คำนวณด้วยวิธีการคำนวณแบบลดต้นลดดอก (effective rate)
        $section->addListItem('การคำนวณดอกเบี้ยเงินกู้ให้คำนวณด้วยวิธีการคำนวณแบบลดต้นลดดอก (effective rate)', 1, null, 'multilevel2');
        //6.	เบี้ยปรับ 
        $section->addListItem('เบี้ยปรับ', 0, null, 'multilevel2');
        $message ='ในกรณีที่ผู้กู้ผิดนัดชำระหนี้หรือชำระหนี้ไม่ถูกต้อง ผู้กู้ตกลงชำระเบี้ยปรับให้แก่บริษัทฯ ในอัตราร้อยละ 5 ต่อปีนับตั้งแต่วันที่ผู้กู้ผิดนัดชำระหนี้หรือชำระหนี้ไม่ถูกต้องจนกว่าจะชำระหนี้ให้แก่บริษัทฯจนหมดสิ้น ทั้งนี้เพื่อให้ปราศจากข้อสงสัย เบี้ยปรับภายใต้ข้อสัญญานี้ให้แยกจากดอกเบี้ยผิดนัดในกรณีที่ผู้กู้ผิดนัดชำระหนี้';
        $section->addListItem($message, 1, null, 'multilevel2');
        //7.	ค่าธรรมเนียมและค่าใช้จ่าย
        $section->addListItem('ค่าธรรมเนียมและค่าใช้จ่าย', 0, null, 'multilevel2');
        //7.1.	ผู้กู้ตกลงชำระค่าธรรมเนียม Roll Over (หากมี) ให้แก่บริษัทฯ ตามอัตราร้อยละ 0.5 ของยอดเงินกู้ที่ยังคงค้างอยู่
        $section->addListItem('ผู้กู้ตกลงชำระค่าธรรมเนียม Roll Over (หากมี) ให้แก่บริษัทฯ ตามอัตราร้อยละ 0.5 ของยอดเงินกู้ที่ยังคงค้างอยู่', 1, null, 'multilevel2');
        //7.2.	ผู้กู้ตกลงรับผิดชอบค่าใช้จ่ายและภาระทางภาษีทั้งหมดที่เกิดขึ้นจากหรือเกี่ยวเนื่องกับเงินกู้ ซึ่งรวมถึงแต่ไม่จำกัดเพียงค่าอากรแสตมป์
        $section->addListItem('ผู้กู้ตกลงรับผิดชอบค่าใช้จ่ายและภาระทางภาษีทั้งหมดที่เกิดขึ้นจากหรือเกี่ยวเนื่องกับเงินกู้ ซึ่งรวมถึงแต่ไม่จำกัดเพียงค่าอากรแสตมป์', 1, null, 'multilevel2');

        //8.	วิธีการชำระเงินกู้
        $section->addListItem('วิธีการชำระเงินกู้', 0, null, 'multilevel2');
        $message='ในการชำระเงินกู้ ผู้กู้จะต้องชำระเงินกู้ให้แก่บริษัทฯ ในวันครบกำหนดชำระเงินตามจำนวนที่ได้ระบุไว้ในเอกสารแนบท้ายสัญญาฯฉบับนี้ โดยโอนเงินเข้าบัญชีตามรายละเอียดบัญชีที่ได้ระบุไว้ในเอกสารแนบท้ายสัญญาฯฉบับนี้';
        $section->addListItem($message, 1, null, 'multilevel2');
        //9.	การเปลี่ยนแปลงข้อมูล
        $section->addListItem('การเปลี่ยนแปลงข้อมูล', 0, null, 'multilevel2');
        $message='ในกรณีที่ผู้กู้ได้ทำการเปลี่ยนแปลงข้อมูลเกี่ยวกับที่อยู่ ผู้ติดต่อ ที่อยู่ของผู้ติดต่อ กรรมการผู้มีอำนาจ หมายเลขโทรศัพท์ อีเมล หรือข้อมูลสำคัญอื่นใดที่บริษัทฯจำต้องรับทราบเพื่อใช้ในการติดต่อผู้กู้ได้นั้น ผู้กู้จะต้องแจ้งให้แก่บริษัทฯทราบถึงการเปลี่ยนแปลงข้อมูลดังกล่าวภายใน 3 วันนับแต่ได้ทำการเปลี่ยนแปลงดังกล่าว';
        $section->addListItem($message, 1, null, 'multilevel2');
        //10.	การเร่งให้ชำระหนี้ทันที
        $section->addListItem('การเร่งให้ชำระหนี้ทันที', 0, null, 'multilevel2');
        $message ='หากมีกรณีใดดังต่อไปนี้เกิดขึ้นกับผู้กู้ ผู้กู้จะสูญเสียสิทธิในเงื่อนเวลาแห่งการชำระเงินทั้งหมดอย่างหลีกเลี่ยงไม่ได้ และผู้กู้จะต้องชำระเงินกู้ทั้งหมดคืนให้แก่บริษัทฯ ให้ครบถ้วนทันที';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message ='(ก)	หากไม่มีการชำระดอกเบี้ยหรือชำระดอกเบี้ยอย่างไม่ถูกต้องตามจำนวนและภายในระยะเวลาที่ระบุไว้ในเอกสารแนบท้ายสัญญาฯฉบับนี้';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message ='(ข)	หากไม่มีการชำระเงินตามตั๋วสัญญาใช้เงินหรือเช็คที่ออกโดยผู้กู้ต่อบริษัทฯหรือเจ้าหนี้รายอื่นของผู้กู้ หรือมีการระงับการชำระเงินตามตั๋วสัญญาใช้เงินหรือเช็คดังกล่าว';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message ='(ค)	หากมีการยึดทรัพย์ ยึดทรัพย์ชั่วคราว พิทักษ์ทรัพย์ หรือมีคำสั่งห้ามชั่วคราว หรือมีการลงโทษสำหรับการชำระเงินล่าช้า';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message='(ง)	หากมีคำสั่งให้ผู้กู้ หรือบุคคลอื่นที่เกี่ยวข้องล้มละลาย ฟื้นฟูกิจการ ชำระบัญชี หรือปรับโครงสร้างบริษัทฯ'; 
        $section->addListItem($message, 1, null, 'multilevel2');
        $message='(จ)	หากผู้กู้ไม่สามารถชำระหนี้ของตนต่อบริษัทฯหรือเจ้าหนี้รายอื่นของผู้กู้ได้โดยทั่วไป เมื่อหนี้นั้นถึงกำหนดชำระ';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message='(ฉ)	หากมีการบอกเลิกสัญญาอื่นที่ได้มีการทำระหว่างบริษัทฯ และผู้กู้';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message='(ช)         ความน่าเชื่อถือของผู้กู้ลดลง';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message='(ซ)	หากผู้กู้ละเลยที่จะแจ้งถึงการเปลี่ยนแปลงข้อมูลตามที่ได้ระบุไว้ในข้อ 9 แห่งสัญญาฯฉบับนี้แก่บริษัทฯ';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message='(ฌ)	หากมีเหตุอันควรที่บริษัทฯ คาดเห็นได้ว่ามีความเป็นไปได้ที่จะเกิดเหตุใดเหตุหนึ่งดังที่ระบุไว้ข้างต้นในข้อ (ก) ถึง (ช)';
        $section->addListItem($message, 1, null, 'multilevel2');
        //11.	เบ็ดเตล็ด
        $section->addListItem('เบ็ดเตล็ด', 0, null, 'multilevel2');
        $message ='มิให้ถือว่าข้อความใด ๆ ในสัญญาฯ นี้เป็นการเข้าทำกิจการร่วมค้า การเข้าเป็นหุ้นส่วน หรือการเข้ารวมกลุ่มในรูปแบบใด ๆ และไม่มีคู่สัญญาใดมีอำนาจในการกระทำการเป็นผู้แทนหรือตัวการของคู่สัญญาอีกฝ่ายหนึ่ง'; 
        $section->addListItem($message, 1, null, 'multilevel2');
        $message ='ให้สัญญาฯ นี้ผูกพันและเป็นประโยชน์แก่คู่สัญญาทั้งสองฝ่าย ผู้สืบสิทธิ และผู้รับโอนสิทธิของคู่สัญญานั้น ๆ ไม่มีข้อสัญญาใดในสัญญาฯ นี้ ไม่ว่าโดยชัดแจ้งหรือโดยปริยาย ที่ถือว่าเป็นการให้สิทธิ ประโยชน์ หรือสิทธิในการเยียวยาแก้ไขแก่บุคคลหรือนิติบุคคลใด ๆ ภายใต้หรือโดยเหตุแห่งสัญญาฯ นี้';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message ='การแก้ไข เพิ่มเติม ยกเลิก หรือเปลี่ยนแปลงใดของสัญญาฯ นี้ย่อมไม่มีผล เว้นแต่จะได้ทำเป็นลายลักษณ์อักษรและลงนามโดยคู่สัญญาทั้งหลาย พร้อมทั้งประทับตราสำคัญของบริษัทฯ (หากมีข้อกำหนด) ไว้';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message ='การที่บริษัทฯ ไม่ได้ใช้สิทธิหรือล่าช้าในการใช้สิทธิหรือการเยียวยาแก้ไขใด ๆ จะไม่มีผลเป็นการสละสิทธิใด ๆ หรือสิทธิที่จะได้รับการเยียวยาแก้ไขตามกฎหมายหรือตามสัญญาฯ ฉบับนี้ อีกทั้งการใช้สิทธิหรือสิทธิในการเยียวยาแก้ไขดังกล่าวครั้งหนึ่งหรือเพียงบางส่วน ไม่ถือเป็นการจำกัดมิให้ใช้สิทธิต่อไปหรือมิให้ใช้สิทธิโดยทางอื่น หรือมิให้ใช้สิทธิอื่นหรือการเยียวยาแก้ไขอื่นได้อีก';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message ='ในกรณีที่สัญญาฯ ฉบับนี้ทำขึ้นเป็นทั้งภาษาไทยและภาษาอังกฤษ เมื่อมีข้อแตกต่างหรือข้อซึ่งขัดหรือแย้งกัน ให้ยึดถือเอาสัญญาฯ ฉบับภาษาไทยเป็นหลักในการตีความสัญญาฯ ฉบับนี้';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message ='สัญญาฯ ฉบับนี้ และเอกสารผนวกแนบท้ายสัญญา ตารางรายการ และเอกสารแนบท้ายต่าง ๆ ซึ่งอ้างถึงไว้ในสัญญาฯ ฉบับนี้ให้ประกอบเป็นข้อตกลงทั้งหมดและความเข้าใจเกี่ยวกับเรื่องนี้ระหว่างคู่สัญญา';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message ='เงื่อนไขและข้อสัญญาที่ได้ระบุไว้ในแต่ละข้อภายใต้สัญญาฯ ฉบับนี้ให้บังคับใช้แยกต่างหากจากข้ออื่น ๆ ในสัญญาฯ นี้ และข้อสัญญาที่ไม่สมบูรณ์ ไม่ชอบด้วยกฎหมาย หรือบังคับใช้ไม่ได้ในเขตอำนาจใด ๆ ย่อมไม่กระทบกระเทือนถึงความสมบูรณ์ ความชอบด้วยกฎหมาย และการมีผลบังคับใช้ของข้อสัญญาข้ออื่น เมื่อมีการพิจารณาว่าเงื่อนไขหรือข้อสัญญาใดไม่สมบูรณ์ ไม่ชอบด้วยกฎหมาย หรือบังคับใช้ไม่ได้ คู่สัญญาจะต้องปรึกษาหารือกันด้วยความสุจริต เพื่อแก้ไขสัญญาฯ ฉบับนี้ให้มีผลใกล้เคียงกับเจตนารมณ์ที่แท้จริงของคู่สัญญาให้มากที่สุดเท่าที่จะเป็นไปได้ เพื่อให้การทำธุรกรรมต่าง ๆ ที่กำหนดไว้ในสัญญาฯ นี้มีผลสมบูรณ์ตามความมุ่งหมายที่แท้จริงมากที่สุดเท่าที่จะเป็นไปได้';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message ='สัญญาฯ นี้ให้ใช้และตีความตามกฎหมายแห่งประเทศไทย กรณีที่เกิดข้อพิพาทที่เกิดจากหรือเกี่ยวข้องกับสัญญาฯ นี้ ให้นำคดีขึ้นสู่ศาลที่มีเขตอำนาจในประเทศไทย';
        $section->addListItem($message, 1, null, 'multilevel2');
        $message ='สัญญาฯ ฉบับนี้ทำขึ้นเป็นสอง (2) ฉบับ มีข้อความถูกต้องตรงกัน โดยคู่สัญญาทั้งสองได้อ่าน เข้าใจ และเห็นชอบข้อความซึ่งได้ระบุไว้ในนี้แล้ว จึงได้ลงลายมือชื่อและประทับตราสำคัญของบริษัทฯ (ถ้ามีข้อกำหนดไว้) ต่อหน้าพยานในวันที่ตามที่ได้ระบุไว้ข้างต้น';
        $section->addText($message,$normal_font_style);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('CodeSolutionStuff.docx');
        return response()->download(public_path('CodeSolutionStuff.docx'));
        return view('agreement');
    }

    public function createAgreement(){
        $order_id = request()->order_id;
        $order = order::find($order_id);
        $suffix = $order->order_number;
        // $file_name = "drawdown_agreement-$suffix.pdf";
        // //create pdf
        // $mpdf = new \Mpdf\Mpdf([
        //         'mode' => 'utf-8',
        //         'format' => 'A4',
        //         'margin_header' => '3',
        //         'margin_top' => '10',
        //         'margin_bottom' => '10',
        //         'margin-left'=>'0',
        //         'margin-right'=>'0',
        //         'margin_footer' => '2',
        //     ]);
        // $mpdf->useDictionaryLBR = false;
        // $file_path = storage_path('/app/public/agreement')."/$file_name";
        // $mpdf->WriteHTML(view('agreement', [
        //     'order' => $order,
        // ]));
        // $mpdf->Output($file_path,'F');
        $installments = $order->product_offering->product->installments;
        if($installments == 1)
            return view('biz_master_agreement')->with('order',$order);
        else
            return view('biz_plus_agreement')->with('order',$order);
    }

    //Route::post('/create_product', [BusinessLoanController::class, 'createProduct']);
    public function createProduct(){
        //return request();
        $product = Product::firstOrCreate([
            'product_code'=>request()->product_code,
            'product_name'=>request()->product_name,
            'terms'=>request()->term,
            'loan_amount'=>str()->replace(',', '', request()->loan_amount),
            'installments'=>request()->installment,
        ]);
        return back();
    }
}