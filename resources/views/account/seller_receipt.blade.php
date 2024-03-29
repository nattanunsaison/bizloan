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
            @php
                $contractor = $record->order->contractor;
            @endphp
            <table style="width:100%">
                <tbody>
                    <tr>
                        @for($i=0;$i < 7;$i++) <td></td> @endfor
                        <td style='text-align:left'><b>บริษัท สยาม เซย์ซอน จำกัด เลขที่ 1 ถนนปูนซิเมนต์ไทย บางซื่อ กรุงเทพฯ 10800</b></td>
                        @for($i=0;$i < 21;$i++) <td></td> @endfor
                        
                    </tr>
                    <tr>
                        @for($i=0;$i < 7;$i++) <td></td> @endfor
                        <td style='text-align:left'>เลขทะเบียนนิติบุคคล : 0105561195866 โทรศัพท์ 02-586-3021</td>
                        @for($i=0;$i < 21;$i++) <td></td> @endfor
                    </tr>
                    <tr>
                        @for($i=0;$i < 7;$i++) <td></td> @endfor
                        <td style='text-align:left'>Siam Saison Co., Ltd. 1 Siam Cement Road, Bangsue, Bangsue, Bangkok 10800</td>
                        @for($i=0;$i < 21;$i++) <td></td> @endfor
                    </tr>
                    <tr>
                        @for($i=0;$i < 7;$i++) <td></td> @endfor
                        <td style='text-align:left'>Tax ID: 0105561195866 Tel. 02-586-3021</td>
                        @for($i=0;$i < 21;$i++) <td></td> @endfor
                    </tr>
                    <tr>
                        @for($i=0;$i < 29;$i++) <td></td> @endfor
                    </tr>
                    <tr>
                        <td colspan='29' style='text-align:center' ><b>ต้นฉบับใบเสร็จรับเงิน</b></td>
                    </tr>
                    <tr>
                        <td colspan='29' style='text-align:center'>RECEIPT (ORIGINAL)</td>
                    </tr>
                </tbody>
            </table>
            @php 
                $dealer = $record->order->dealer;
                $repayment_ymd = \Carbon\Carbon::parse($record->receive_ymd)->addYears(543)->locale('TH-th')->isoFormat('D MMMM YYYY');
            @endphp
            <table style="width:100%">
                <tbody>
                    <tr>
                        @for($i=0;$i < 22;$i++) <td></td> @endfor
                        <td colspan='3' style='text-align:left'>เลขที่ (No)</td>
                        <td style='text-align:left'>{{$record->seller_receipt_number}}</td>
                    </tr>
                    <tr>
                        @for($i=0;$i < 22;$i++) <td></td> @endfor
                        <td colspan='3' style='text-align:left'>วันที่ (Date)</td>
                        <td style='text-align:left'>{{$repayment_ymd}}</td>
                    </tr>
                    <tr>
                        <td colspan='3' style='text-align:left'>ชื่อ (Name)</td>
                        <td colspan='12' style='text-align:left'>{{$dealer->dealer_name}}</td>
                        <td colspan='7' style='text-align:left'>เลขประจำตัวผู้เสียภาษี (Tax ID)</td>
                        <td colspan='4' style='text-align:left'>{{$dealer->tax_id}}</td>
                        <td colspan='3' style='text-align:left'>สาขา: xxx</td>
                    </tr>
                    <tr>
                        <td colspan='4' style='text-align:left'>ที่อยู่ (Address)</td>
                        <td style='text-align:left' colspan='25'>{{$dealer->address}}</td>
                    </tr>
                </tbody>
            </table>
            <table style="width:100%">
                <thead>
                    <tr>
                        <th colspan='4' style='text-align:center'>เลขที่รายการ <br>Order number</th>
                        <th colspan='4' style='text-align:center'>ชำระเงินต้น <br>Principal</th>
                        <th colspan='4' style='text-align:center'>ชำระดอกเบี้ย <br>Interest</th>
                        <th colspan='4' style='text-align:center'>ชำระเบี้ยปรับล่าช้า <br>Delay penalty</th>
                        <th colspan='4' style='text-align:center'>ชำระเกิน <br>Exceeded amount</th>
                        <th colspan='4' style='text-align:center'>เครดิตเงินคืน <br>Cashback</th>
                        <th colspan='5' style='text-align:center'>ยอดรวมชำระ <br>Total paid amount</th>
                    </tr>
                </thead>
                <tbody>

                @php 
                    $order = $record->order;
                    $receive_amount_detail = $record->receive_amount_detail;
                    $paid_total = $receive_amount_detail->principal + $receive_amount_detail->paid_interest + $receive_amount_detail->paid_late_charge;
                    $input_ymd = \Carbon\Carbon::parse($order->input_ymd);
                    $receive_ymd = \Carbon\Carbon::parse($record->receive_ymd);
                    $datediff = $receive_ymd->diffInDays($input_ymd);
                @endphp

                <tr>
                    <td colspan='4' style='text-align:center'>{{$order->order_number}} </td>
                    <td colspan='4' style='text-align:right'>{{$receive_amount_detail->principal}}</td>
                    <td colspan='4' style='text-align:right'>{{$receive_amount_detail->paid_interest}}</td>
                    <td colspan='4' style='text-align:right'>{{$receive_amount_detail->paid_late_charge ?? 0}}</td>
                    <td colspan='4' style='text-align:right'>{{$receive_amount_detail->exceeded_amount ?? 0}} </td>
                    <td colspan='4' style='text-align:right'>0 </td>
                    <td colspan='5' style='text-align:right'>{{$paid_total}} </td>
                </tr>
                @for($i=0;$i < 2;$i++)
                <tr>
                    <td colspan='4' style='color:white'>{{1}}</td>
                    <td colspan='20'style='color:white'>รับเงินตามสัญญาโอนสิทธิ์</td>
                    <td colspan='5' style='color:white'>{{$record->receive_amount}}</td>
                </tr>
                @endfor
                <tr>
                    <td colspan='17' style='color:white'>{{1}}</td>
                    <td colspan='7' >เงินต้น 10%</td>
                    <td colspan='5' style='text-align:right' >{{round($receive_amount_detail->principal*0.1,2)}}</td>
                </tr>
                <tr>
                    <td colspan='17' style='color:white'>{{1}}</td>
                    <td colspan='7' >หัก ดอกเบี้ยและค่าปรับล่าช้า</td>
                    <td colspan='5' style='text-align:right'>{{$receive_amount_detail->paid_interest+ $receive_amount_detail->paid_late_charge}}</td>
                </tr>
                <tr>
                    <td colspan='17' style='color:white'>{{1}}</td>
                    <td colspan='7' >บวกภาษีหัก ณ ที่จ่าย 1%</td>
                    <td colspan='5' style='text-align:right'>{{$receive_amount_detail->paid_tax}}</td>
                </tr>
                <tr>
                    <td colspan='17' style='color:white'>{{1}}</td>
                    <td colspan='7' >ยอดโอนสุทธิ</td>
                    <td colspan='5' style='text-align:right'>{{$receive_amount_detail->payback_amount}}</td>
                </tr>
                @for($i=0;$i < 2;$i++)
                <tr>
                    <td colspan='4' style='color:white'>{{1}}</td>
                    <td colspan='20'style='color:white'>รับเงินตามสัญญาโอนสิทธิ์</td>
                    <td colspan='5' style='color:white'>{{$record->receive_amount}}</td>
                </tr>
                @endfor
                <tr>
                    <td colspan='24' style='text-decoration:underline'>รายละเอียดการคำนวณดอกเบี้ย</td>
                    <td colspan='5' style='color:white'></td>
                </tr>
                <tr>
                    <td colspan='4' >เงินต้น</td>
                    <td colspan='4' style='text-align:right'>{{$order->purchase_amount}}</td>
                    <td colspan='16' style='color:white'></td>
                    <td colspan='5' style='color:white'></td>
                </tr>
                <tr>
                    <td colspan='4' >อัตรา</td>
                    <td colspan='4' style='text-align:right'>6%</td>
                    <td colspan='16' style='color:white'></td>
                    <td colspan='5' style='color:white'></td>
                </tr>
                <tr>
                    <td colspan='4' >Input date</td>
                    <td colspan='4' style='text-align:right'>{{$input_ymd->copy()->isoFormat('D MMMM YYYY')}}</td>
                    <td colspan='16' style='color:white'></td>
                    <td colspan='5' style='color:white'></td>
                </tr>
                <tr>
                    <td colspan='4' >Receive date</td>
                    <td colspan='4' style='text-align:right'>{{$receive_ymd->copy()->isoFormat('D MMMM YYYY')}}</td>
                    <td colspan='16' style='color:white'></td>
                    <td colspan='5' style='color:white'></td>
                </tr>
                <tr>
                    <td colspan='4' >จำนวนวัน</td>
                    <td colspan='4'>{{$datediff}}</td>
                    <td colspan='16' style='color:white'></td>
                    <td colspan='5' style='color:white'></td>
                </tr>
                <tr>
                    <td colspan='4' >WHT (1%)</td>
                    <td colspan='4' style='text-align:right'>{{$receive_amount_detail->paid_tax}}</td>
                    <td colspan='16' style='color:white'></td>
                    <td colspan='5' style='color:white'></td>
                </tr>
                @for($i=0;$i < 2;$i++)
                <tr>
                    <td colspan='4' style='color:white'>{{1}}</td>
                    <td colspan='20'style='color:white'>รับเงินตามสัญญาโอนสิทธิ์</td>
                    <td colspan='5' style='color:white'>{{$record->receive_amount}}</td>
                </tr>
                    
                @endfor
                <tr>
                    <td colspan='19'>ตัวอักษร ({{$amount_read}})</td>
                    <td colspan='5'>จำนวนเงิน (TOTAL)</td>
                    <td colspan='5' style='text-align:right'>{{$paid_total}}</td>
                </tr>
                </tbody>
            </table>
            <table style="width:100%;border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td>&#9744; เงินสด (CASH)</td>
                        @for($i=0;$i < 28;$i++) <td></td> @endfor
                    </tr>
                    <tr>
                        <td colspan='8' style='text-align:left'>&#9744; เช็คธนาคาร (BANK CHEQUE)</td>
                        <td colspan='9' style='text-align:right'>xxx</td>
                        <td colspan='3' style='text-align:right'>เลขที่ (NO.)</td>
                        <td colspan='9'>xxx</td>      
                    </tr>
                    <tr>
                        <td colspan='4' style='text-align:left'>ลงวันที่ (DATE)</td>
                        <td colspan='5' style='text-align:right'>{{\Carbon\Carbon::parse($record->receive_ymd)->addYears(543)->locale('th')->isoFormat('LL')}}</td>
                        <td colspan='4' style='text-align:right'>สาขา (BRANCH)</td>
                        <td colspan='9' style='text-align:right'>xxx</td>
                        <td colspan='7' style='text-align:center'>บริษัท สยามเซย์ซอน จำกัด</td>
                    </tr>
                    <tr>
                        <td colspan='7' style='text-align:left'>&#9745; โอนเงินเข้าบัญชี</td>
                        <td colspan='15' style='text-align:left'>ธนาคารไทยพาณิชย์ กระแสรายวัน เลขที่ 111-3-98830-0</td>
                        @for($i=0;$i < 7;$i++) <td></td> @endfor
                    </tr>
                    <tr>
                        @for($i=0;$i < 29;$i++) <td></td> @endfor
                    </tr>
                    <tr>
                        @for($i=0;$i < 22;$i++) <td></td> @endfor
                        <td colspan='7' style='text-align:center'>{{auth()->user()->name}}</td>
                    </tr>
                    <tr>
                        @for($i=0;$i < 22;$i++) <td></td> @endfor
                        <td colspan='7' style='text-align:center'>ผู้รับมอบอำนาจ</td>
                    </tr>
                    <tr>
                        <td colspan='6' style='text-align:left'>ผู้รับเงิน (COLLECTOR)</td>
                        <td colspan='8' style='text-align:left'>{{auth()->user()->name}}</td>
                        <td colspan='4' style='text-align:right'>ลงวันที่ (DATE)</td>
                        <td colspan='4' style='text-align:right'>{{\Carbon\Carbon::parse($record->receive_ymd)->addYears(543)->locale('th')->isoFormat('LL')}}</td>
                        <td colspan='7' style='text-align:center'>(AUTHORIZED SIGNATURE)</td>
                    </tr>
                    <tr> 
                        <td colspan='29' style='text-align:left'>***หมายเหตุ : กรณีชำระเงินด้วยเช็ค ใบเสร็จรับเงินนี้จะเสร็จสมบูรณ์ต่อเมื่อเช็คได้รับการชำระเงินแล้วเท่านั้น</td> 
                    </tr>
                    <tr> 
                        <td colspan='29' style='text-align:left'>กรณีชำระเงินโดยเงินเงินเข้าบัญชีธนาคาร ใบเสร็จรับเงินนี้ จะสมบูรณ์ตอเมื่อได้รับเงินโอนเข้าบัญชีแล้วเท่านั้น</td> 
                    </tr>  
                </tbody>
            </table>
            @if(request()->has('mode'))
            <button class='button' onclick="window.location='{{url('download/seller_receipt?id=')}}{{$record->id}}'">Export this receipt</button>
            <button class='button' onclick="window.location='{{url('dealer_payment/scb_template?id=')}}{{$record->id}}'">Create SCB template</button>
            <button class='button' onclick="window.location='{{url('buyer_receipt?id=')}}{{$record->id}}&mode=view'">Create Receipt for Buyer</button>
            <button class='button' onclick="window.location='{{url('payment_voucher?id=')}}{{$record->id}}&mode=view'">Create payment voucher</button>
            @endif
        </div>
    </body>
</html>