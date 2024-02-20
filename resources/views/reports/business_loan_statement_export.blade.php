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
            <div><img style="width:15%;" class="object-cover" src="{{config('constants.app_url')}}/saison_credit.png"></div>
            @php
                // $dealer = $record->order->dealer;
                // $addresses = explode("\n", $dealer->address);
            @endphp
            <table style="width:100%">
                <tbody>
                    <tr>
                        {{-- customer name --}}
                        <td colspan='1' style='text-align:left'>Ref: {{ $order->order_number }}</td>
                        <td colspan='1' style=''></td>
                        {{-- @for($i=0;$i < 28;$i++) <td></td> @endfor --}}
                    </tr>
                    <tr>
                        {{-- customer name --}}
                        <td colspan='1' style='text-align:left'>{{ $order->th_company_name }}</td>
                        <td colspan='1' style=''></td>
                        {{-- @for($i=0;$i < 28;$i++) <td></td> @endfor --}}
                    </tr>
                    <tr>
                        {{-- customer address --}}
                        <td colspan='1' style='text-align:left'>{{ $order->customer_address }}</td>
                        <td colspan='1' style=''></td>
                        {{-- @for($i=0;$i < 28;$i++) <td></td> @endfor --}}
                    </tr>
                    <tr>
                        <td colspan='1' style='text-align:left'>เลขทะเบียนนิติบุคคล : {{ $order->tax_id }}</td>
                        <td colspan='1' style=''></td>
                        {{-- @for($i=0;$i < 28;$i++) <td></td> @endfor --}}
                    </tr>
                    <tr>
                        <td colspan='1' style=''></td>
                        <td colspan='1' style='text-align:right'><h2>ใบแจ้งยอดรายการเซย์ซอนเครดิต/ใบแจ้งหนี้</h2></td>
                        {{-- <td><img class="object-cover" src="{{config('constants.app_url')}}/siamsaisonlogo.png"></td> --}}
                    </tr>
                    <tr>
                        {{-- <td><h2>ใบแจ้งยอดรายการเซย์ซอนเครดิต/ใบแจ้งหนี้</h2></td> --}}
                        <td colspan='1' style=''></td>
                        <td colspan='1' style='text-align:right; width:50%;'><img style="width:40%;" class="object-cover" src="{{config('constants.app_url')}}/siamsaisonlogo.png"></td>
                    </tr>
                    <tr>
                        <td colspan='1' style='text-align:left'></td>
                        {{-- @for($i=0;$i < 27;$i++) <td></td> @endfor --}}
                        <td colspan='1' style='text-align:right'><b>บริษัท สยาม เซย์ซอน จำกัด</b></td>
                    </tr>
                    <tr>
                        <td colspan='1' style='text-align:left'><b></b></td>
                        {{-- @for($i=0;$i < 27;$i++) <td></td> @endfor --}}
                        <td colspan='1' style='text-align:right'>เลขที่ 1 ถนนปูนซิเมนต์ไทย บางซื่อ กรุงเทพฯ 10800</td>
                    </tr>
                    <tr>
                        <td colspan='1' style='text-align:left'></td>
                        {{-- @for($i=0;$i < 27;$i++) <td></td> @endfor --}}
                        <td colspan='1' style='text-align:right'>เลขทะเบียนนิติบุคคล : 0105561195866 โทรศัพท์ 02-586-3021</td>
                    </tr>
                    <tr>
                        <td colspan='1' style='text-align:left'></td>
                        {{-- @for($i=0;$i < 27;$i++) <td></td> @endfor --}}
                        <td colspan='1' style='text-align:right'>เวลาทำการ จันทร์ - ศุกร์ 09.00 - 17.00 ยกเว้นวันหยุดนักขัตฤกษ์</td>
                    </tr>
                    <tr style="background-color:#FF0000"> <td colspan='29'></td> <tr>
                    <tr>
                        <td colspan='16' style='text-align:center'>วันโอนเงิน</td>
                        <td colspan='13' style='text-align:center'>{{ $order->purchase_ymd_text }}</td>
                    </tr>
                    <tr style="background-color:#FF0000"> <td colspan='29'></td> <tr>
                    <tr>
                        <td colspan='16' style='text-align:center'>วันครบกำหนดชำระเงิน</td>
                        <td colspan='13' style='text-align:center'>{{ $order->due_ymd_text }}</td>
                    </tr>
                    <tr style="background-color:#FF0000"> <td colspan='29'></td> <tr>
                </tbody>
            </table>
            <h4>รายละเอียดสัญญา</h4>
            <table style="width:100%">
                {{-- <thead> --}}
                    <tr>
                        <td colspan='1'></td>
                        <td colspan='1' style='text-align:left'>ยอดเงินกู้</td>
                        <td colspan='1' style='text-align:left'>{{ $order->loan_amount }}</td>
                        <td colspan='1'></td>
                        {{-- <td></td> --}}
                    </tr>
                    <tr>
                        <td colspan='1'></td>
                        <td colspan='1' style='text-align:left'>ระยะเวลา (วัน)</td>
                        <td colspan='1' style='text-align:left'>{{ $order->terms }}</td>
                        <td colspan='1'></td>
                    </tr>
                    <tr>
                        <td colspan='1'></td>
                        <td colspan='1' style='text-align:left'>อัตราดอกเบี้ย (%)</td>
                        <td colspan='1' style='text-align:left'>{{ $order->interest_rate }}</td>
                        <td colspan='1'></td>
                    </tr>
                    <tr>
                        <td colspan='1'></td>
                        <td colspan='1' style='text-align:left'>อัตราดอกเบี้ยปรับผิดนัดชำระ (%)</td>
                        <td colspan='1' style='text-align:left'>{{ $order->delay_penalty_rate }}</td>
                        <td colspan='1'></td>
                    </tr>
                    @if ( $order->discount_rate > 0 )
                        <tr>
                            <td colspan='1'></td>
                            <td colspan='1' style='text-align:left'>อัตราส่วนลดดอกเบี้ย (%)</td>
                            <td colspan='1' style='text-align:left'>{{ $order->discount_rate }}</td>
                            <td colspan='1'></td>
                        </tr>
                    @endif
                {{-- </thead>
                <tbody>
                </tbody> --}}
            </table>
            <h4>รายละเอียดการชำระ</h4>
            <table style="width:100%">
                {{-- <thead> --}}
                {{-- <tr>
                    <th colspan='2'></th>
                    <th colspan='2' style='text-align:center'>ยอดเงินต้นคงค้าง</th>
                    <th colspan='9' style='text-align:center'>ยอดดอกเบี้ยคงค้าง</th>
                </tr> --}}
                
                <tr>
                    <td colspan='1'></td>
                    <td colspan='1'></td>
                    <td colspan='1' style='text-align:left'>จำนวนเงิน(บาท)</td>
                    <td colspan='1'></td>
                </tr>
                <tr>
                    <td colspan='1'></td>
                    <td colspan='1' style='text-align:left'>ยอดเงินต้นคงค้าง</td>
                    <td colspan='1' style='text-align:left'>{{ $order->bill_principal }}</td>
                    <td colspan='1'></td>
                </tr>
                <tr>
                    <td colspan='1'></td>
                    <td colspan='1' style='text-align:left'>ยอดดอกเบี้ยคงค้าง</td>
                    <td colspan='1' style='text-align:left'>{{ $order->bill_interest }}</td>
                    <td colspan='1'></td>
                </tr>
                <tr>
                    <td colspan='1'></td>
                    <td colspan='1' style='text-align:left'>ยอดรวมที่ต้องชำระงวดนี้</td>
                    <td colspan='1' style='text-align:left'>{{ $order->bill_total }}</td>
                    <td colspan='1'></td>
                </tr>
                {{-- </thead>
                <tbody> --}}

                {{-- @php
                @endphp
                <tr>
                    <td colspan='2' style='text-align:center'>จำนวนเงิน(บาท)</td>
                    <td colspan='5' style='text-align:right'></td>
                    <td colspan='9' style='text-align:right'></td>
                </tr>

                <tr>
                    <td colspan='24'>ยอดรวมที่ต้องชำระงวดนี้</td>
                    <td colspan='5' style='text-align:right'></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> --}}
                {{-- </tbody> --}}
            </table>
            <table style="width:100%;border-collapse: collapse;">
                <tr style="background-color:black"> <td colspan='4'></td> <tr>
                <tr>
                    <td style='text-align:left; font-size:14px;' colspan='4'>หมายเหตุ : ยอดชำระนับถึงวันที่ระบบทำรายการ (อ่าจต่างจากยอดชำระจริง)</td>
                </tr>
                <tr>
                    <td colspan='14'></td>
                </tr>
                <tr style="background-color:#FF0000; color: white;"> <td colspan='29' style='text-align:center'>ช่องทางการชำระเงิน และการนำส่งหลักฐานการชำระเงิน</td> <tr>
                <tr>
                    <td colspan='1'>ธนาคาร</td>
                    <td colspan='1'>ไทยพาณิชย์ จำกัด (มหาชน)</td>
                    <td colspan='2'>กรุณานำส่งหลักฐานการชำระเงินให้แก่พนักงานขาย บริษัทสยาม</td>
                </tr>
                <tr>
                    <td colspan='1'>ชื่อบัญชี</td>
                    <td colspan='1'>บริษัท สยาม เซย์ซอน จำกัด</td>
                    <td colspan='2'>เซย์ซอน เมื่อท่านทำการชำระเงินเรียบร้อยแล้ว</td>
                </tr>
                <tr>
                    <td colspan='1'>เลขที่บัญชี</td>
                    <td colspan='1'>111-3-93830-0</td>
                    <td colspan='2'></td>
                </tr>
                <tr>
                    <td colspan='1'>ประเภทบัญชี</td>
                    <td colspan='1'>กระแสรายวัน</td>
                    <td colspan='2'></td>
                </tr>
                <tr>
                    <td colspan='1'></td>
                    <td colspan='1'></td>
                    <td colspan='2'></td>
                </tr>
                <tr>
                    <td colspan='1'></td>
                    <td colspan='1'></td>
                    <td colspan='2'></td>
                </tr>
                <tr>
                    <td colspan='1'></td>
                    <td colspan='1'></td>
                    <td colspan='2'></td>
                </tr>
                <tr style="background-color:black;"><td colspan='4'></td><tr>
                <tr style=""><td colspan='4' style='text-align:center'>กรุณาชำระภายในกำหนดเพื่อหลีกเลี่ยงเบี้ยปรับชำระล่าช้า</td><tr>
                <tr style="background-color:black;"><td colspan='4'></td><tr>
                </table>
            {{-- @if(request()->has('mode'))
            <button class='button' onclick="window.location='{{url('download/dealer_statement?id=')}}{{$record->id}}'">Export this statement</button>
            <button class='button' onclick="window.location='{{url('dealer_payment/scb_template?id=')}}{{$record->id}}'">Create SCB template</button>
            <button class='button' onclick="window.location='{{url('buyer_receipt?id=')}}{{$record->id}}&mode=view'">Create Receipt for Buyer</button>
            <button class='button' onclick="window.location='{{url('payment_voucher?id=')}}{{$record->id}}&mode=view'">Create payment voucher</button>
            @endif --}}
        </div>
    </body>
</html>