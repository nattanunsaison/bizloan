<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        @if(request()->has('mode'))
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link href="https://fonts.googleapis.com/css2?family=Trirong:wght@400&family=Trirong:wght@400&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
        <style>
            .content{
                margin: 10px;
            }
            .button {
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 2px 2px;
                cursor: pointer;
                background-color: #008CBA;
                font-family: 'Nunito','Trirong';
                border-radius: 2px;
            }
            body {
                font-family: 'Nunito','Trirong';
                line-height: 1.15;
                font-size: 13px;
                width: 21cm;
                height: 29.7cm;
                box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
                align: center;
                margin: 10px auto;
            }
            h2{
                padding-top: 10px;
            }
        </style>
        @endif
    </head>
    <body size='A4'>
        <div class='content'>
            <h2>ใบสรุปยอดรายการเงินคืน (สำหรับSupplier)</h2>
            @php
                $dealer = $record->order->dealer;
                $addresses = explode("\n", $dealer->address);
            @endphp
            <table style="width:100%">
                <tbody>
                    <tr>
                        <td style='text-align:left'>{{$dealer->dealer_name}}</td>
                        @for($i=0;$i < 28;$i++) <td></td> @endfor
                    </tr>
                    <tr>
                        <td style='text-align:left'>{{$addresses[0]}}</td>
                        @for($i=0;$i < 28;$i++) <td></td> @endfor
                    </tr>
                    <tr>
                        <td style='text-align:left'>เลขทะเบียนนิติบุคคล {{$dealer->tax_id}}</td>
                        @for($i=0;$i < 28;$i++) <td></td> @endfor
                    </tr>
                    <tr>
                        <td style='text-align:left'>{{$addresses[1]}}</td>
                        @for($i=0;$i < 27;$i++) <td></td> @endfor
                        <td style='text-align:right'><b>บริษัท สยาม เซย์ซอน จำกัด</b></td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>{{$addresses[2]}}</b></td>
                        @for($i=0;$i < 27;$i++) <td></td> @endfor
                        <td style='text-align:right'>เลขที่ 1 ถนนปูนซิเมนต์ไทย บางซื่อ กรุงเทพฯ 10800</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'>{{$addresses[3]}}</td>
                        @for($i=0;$i < 27;$i++) <td></td> @endfor
                        <td style='text-align:right'>เลขทะเบียนนิติบุคคล : 0105561195866 โทรศัพท์ 02-586-3021</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'>{{$addresses[4]}}</td>
                        @for($i=0;$i < 27;$i++) <td></td> @endfor
                        <td style='text-align:right'>เวลาทำการ จันทร์ - ศุกร์ 09.00 - 17.00 ยกเว้นวันหยุดนักขัตฤกษ์</td>
                    </tr>
                    <tr>
                        <td colspan='15' style='text-align:left'> วันที่สรุปยอดรายการ: {{\Carbon\Carbon::parse($record->created_at)->addYears(543)->locale('TH-th')->isoFormat('DD MMMM YYYY')}}</td>
                        <td colspan='14' style='text-align:right'>เลขที่อ้างอิง:Get ref no. From SSA</td>
                    </tr>
                    <tr style="background-color:#FF0000"> <td colspan='29'></td> <tr>
                    <tr>
                        <td colspan='16' style='text-align:center'>วันที่นำส่งข้อมูล (โดยBuyer)</td>
                        <td colspan='13' style='text-align:center'>{{\Carbon\Carbon::parse($record->order->input_ymd)->addYears(543)->locale('TH-th')->isoFormat('DD MMMM YYYY')}}</td>
                    </tr>
                    <tr style="background-color:#FF0000"> <td colspan='29'></td> <tr>
                    <tr>
                        <td colspan='16' style='text-align:center'>วันรับชำระจากBuyer</td>
                        <td colspan='13' style='text-align:center'>{{\Carbon\Carbon::parse($record->receive_ymd)->addYears(543)->locale('TH-th')->isoFormat('DD MMMM YYYY')}}</td>
                    </tr>
                    <tr style="background-color:#FF0000"> <td colspan='29'></td> <tr>
                </tbody>
            </table>
            <h4>รายละเอียดรายการที่นำส่งข้อมูล</h4>
            <table style="width:100%">
                <thead>
                <tr>
                    <th colspan='2' style='text-align:center'>รายการที่</th>
                    <th colspan='5' style='text-align:center'>เลขที่รายการ</th>
                    <th colspan='9' style='text-align:center'>ชื่อลูกค้า</th>
                    <th colspan='5' style='text-align:center'>ยอดก่อนภาษีมูลค่าเพิ่ม</th>
                    <th colspan='3' style='text-align:center'>ภาษีมูลค่าเพิ่ม(7%)</th>
                    <th colspan='5' style='text-align:center'>ยอดรวมภาษีมูลค่าเพิ่ม</th>
                </tr>
                </thead>
                <tbody>

                @php 
                    $order = $record->order;
                    $ten_percent_buffer = $order->purchase_amount*0.1;
                    $vat = floor($ten_percent_buffer*7/107 * 100) / 100;
                    $ex_vat = $ten_percent_buffer - $vat;
                @endphp
                <tr>
                    <td colspan='2' style='text-align:center'>{{1}}</td>
                    <td colspan='5'>{{$order->order_number}}</td>
                    <td colspan='9'>{{$order->contractor->th_company_name}}</td>
                    <td colspan='5' style='text-align:right'>{{$ex_vat}}</td>
                    <td colspan='3' style='text-align:right' >{{$vat}}</td>
                    <td colspan='5' style='text-align:right'>{{$order->purchase_amount}}</td>
                </tr>

                <tr>
                    <td colspan='24'>ยอดรวม (เพื่อคำนวณ Transaction fee)</td>
                    <td colspan='5' style='text-align:right'>{{$order->purchase_amount}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
            <table style="width:100%;border-collapse: collapse;">
                @php 
                    $total_amount = $ten_percent_buffer;
                    $transaction_fee = round(0,2);
                    
                    $wht = round(0,2);
                    $net_deduction = $transaction_fee +$vat - $wht;
                    $net_pay = $total_amount - $net_deduction;
                    $repayment_date = $record->receive_ymd;
                    $delay_penalty_and_interest = $record->order->installments->first()->delayPenaltyWithDate($repayment_date);
                    $daily_interest = $delay_penalty_and_interest['daily_interest'];
                    $tax = floor($daily_interest*1/100*100)/100;
                    if(is_null($record->exemption_late_charge))
                        $delay_penalty = $delay_penalty_and_interest['delay_penalty'];
                    else 
                        $delay_penalty = 0;

                    $net_pay = $ten_percent_buffer - $daily_interest - $delay_penalty + $tax;
                @endphp
                
                <tr style="background-color:#FF0000"> <td colspan='29'></td> <tr>
                <tr>
                    <td colspan='9'>◆(A) ยอดเรียกเก็บSupplier (โดย บ. สยามเซย์ซอน)</td>
                    <td colspan='3' style='text-align:right'>{{$total_amount}}</td>
                    <td colspan='2'>บาท</td>
                    <td colspan='10'>◆(B) ยอดชำระตามรายการธุรกรรม (ก่อนหักค่าบริการ)</td>
                    <td colspan='3' style='text-align:right'>{{$ten_percent_buffer}}</td>
                    <td colspan='2' style='text-align:left'>บาท</td>
                </tr>
                <tr>
                    <td colspan='7' style='text-align:left'>ดอกเบี้ย</td>
                    <td colspan='2' style='text-align:right'>interest</td>
                    <td colspan='3' style='text-align:right'>{{$daily_interest}}</td>
                    <td colspan='2'>บาท</td>
                    <td colspan='15' rowspan='4' style='border:1px solid'>{!!nl2br($dealer->bank_account)!!}</td>
                </tr>
                <tr>
                    <td colspan='7' style='text-align:left'>ภาษี</td>
                    <td colspan='2' style='text-align:right'>0.01</td>
                    <td colspan='3' style='text-align:right'>{{$tax}}</td>
                    <td>บาท</td>
                </tr>
                <tr>
                    <td colspan='7' style='text-align:left'>เบี้ยปรับล่าช้า</td>
                    <td colspan='2' style='text-align:right'>delay penalty</td>
                    <td colspan='3' style='text-align:right'>{{$delay_penalty}}</td>
                    <td>บาท</td>
                </tr>
                <tr>
                    <td colspan='14'>{{$record->exemption_late_charge}}</td>
                </tr>
                <tr style="background-color:#FF0000"> <td colspan='29' style='text-align:center'>(โปรดเตรียมเอกสารหนังสือรับรองหักภาษี ณ ที่จ่าย 3% ตามยอดภาษีที่ระบุไว้ โดยลงวันที่ วันโอนเงิน เพื่อเป็นหลักฐานการหักภาษี)</td> <tr>
                <tr style='border-bottom: 1pt solid black;'>
                    <td colspan='24'>◆◆ ยอดสุทธิ (ยอดชำระหักค่าบริการ) ชำระโดย บ.สยามเซย์ซอน ให้ผู้แทนจำหน่าย (B-A)</td>
                    <td colspan='3' style='text-align:right'>{{$net_pay}}</td>
                    <td>บาท</td>
                </tr>
                <tr>
                    <td colspan='25' style='text-align:right'>◆◆วันที่โอนเงิน</td>
                    <td colspan='4' style='text-align:center'>Get pay date from SSA{{--\Carbon\Carbon::parse($order->pay_ymd)->addYears(543)->locale('TH-th')->isoFormat('DD MMMM YYYY')--}}</td>
                </tr>
            </table>
            @if(request()->has('mode'))
            <button class='button' onclick="window.location='{{url('download/dealer_statement?id=')}}{{$record->id}}'">Export this statement</button>
            <button class='button' onclick="window.location='{{url('dealer_payment/scb_template?id=')}}{{$record->id}}'">Create SCB template</button>
            <button class='button' onclick="window.location='{{url('buyer_receipt?id=')}}{{$record->id}}&mode=view'">Create Receipt for Buyer</button>
            <button class='button' onclick="window.location='{{url('payment_voucher?id=')}}{{$record->id}}&mode=view'">Create payment voucher</button>
            @endif
        </div>
    </body>
</html>