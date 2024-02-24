<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">      
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
            h2{
                padding-top: 10px;
            }
            .grid-container {
                display: grid;
                grid-template-columns: auto auto;
                padding-top: 8px;
            }
            .grid-item {
                background-color: rgba(255, 255, 255, 0.8);
                font-size: 20px;
                text-align: center;
                font-weight: bold;
            }
            body {
                font-family: 'browallia';
            }
        </style>
    </head>
    <body>
        <div class='content'>
            <table style="width:100%">
                <tr>
                    {{-- customer name --}}
                    <td colspan='10' style='text-align:left;font-size:20px'></td>
                    <td colspan='19' style='text-align:right;font-size:20px'><b><div style='text-align:right;padding-top:10px'>ใบแจ้งยอดรายการเซย์ซอนเครดิต/ใบแจ้งหนี้</div></b></td>
                </tr>
            </table>
            <table style="width:100%">
                <tbody>
                    <tr>
                        {{-- customer name --}}
                        <td colspan='14' style='text-align:left;font-size:15px'><b>Ref: {{ $order->order_number }}</b></td>
                        <td colspan='15' style='text-align:right;font-size:15px'><b>บริษัท สยาม เซย์ซอน จำกัด</b></td>
                    </tr>
                    <tr>
                        {{-- customer name --}}
                        <td colspan='14' style='text-align:left'>{{ $order->th_company_name }}</td>
                        <td colspan='15' style='text-align:right'>เลขที่ 1 ถนนปูนซิเมนต์ไทย บางซื่อ กรุงเทพฯ 10800</td>
                    </tr>
                    <tr>
                        {{-- customer address --}}
                        <td colspan='14' style='text-align:left'>{{ $order->customer_address }}</td>
                        <td colspan='15' style='text-align:right'>เลขทะเบียนนิติบุคคล : 0105561195866 โทรศัพท์ 02-586-3021</td>
                    </tr>
                    <tr>
                        <td colspan='14' style='text-align:left'>เลขทะเบียนนิติบุคคล : {{ $order->tax_id }}</td>
                        <td colspan='15' style='text-align:right'>เวลาทำการ จันทร์ - ศุกร์ 09.00 - 17.00 ยกเว้นวันหยุดนักขัตฤกษ์</td>
                    </tr>
                    <tr>
                        <td colspan='29'></td>
                    </tr>
                    <tr>
                        <td colspan='14' style='text-align:center'>วันโอนเงิน</td>
                        <td colspan='15' style='text-align:center'>{{ $order->purchase_ymd_text }}</td>
                    </tr>
                    <tr>
                        <td colspan='14' style='text-align:center'>วันครบกำหนดชำระเงิน</td>
                        <td colspan='15' style='text-align:center'>{{ $order->due_ymd_text }}</td>
                    </tr>
                </tbody>
            </table>
            <h4>รายละเอียดสัญญา</h4>
            <table style="width:100%">
                    <tr>
                        <td colspan='1'></td>
                        <td colspan='13' style='text-align:left'>ยอดเงินกู้</td>
                        <td colspan='10' style='text-align:right'>{{ $order->loan_amount }}</td>
                        <td colspan='5'>x</td>
                        {{-- <td></td> --}}
                    </tr>
                    <tr>
                        <td colspan='1'></td>
                        <td colspan='13' style='text-align:left'>ระยะเวลา (วัน)</td>
                        <td colspan='10' style='text-align:right'>{{ $order->terms }}</td>
                        <td colspan='5'>x</td>
                    </tr>
                    <tr>
                        <td colspan='1'></td>
                        <td colspan='13' style='text-align:left'>อัตราดอกเบี้ย (%)</td>
                        <td colspan='10' style='text-align:right'>{{ $order->interest_rate }}</td>
                        <td colspan='5'>x</td>
                    </tr>
                    <tr>
                        <td colspan='1'></td>
                        <td colspan='13' style='text-align:left'>อัตราดอกเบี้ยปรับผิดนัดชำระ (%)</td>
                        <td colspan='10' style='text-align:right'>{{ $order->delay_penalty_rate }}</td>
                        <td colspan='5'>x</td>
                    </tr>
                    @if ( $order->discount_rate > 0 )
                        <tr>
                            <td colspan='1'></td>
                            <td colspan='13' style='text-align:left'>อัตราส่วนลดดอกเบี้ย (%)</td>
                            <td colspan='10' style='text-align:right'>{{ $order->discount_rate }}</td>
                            <td colspan='5'></td>
                        </tr>
                    @endif
            </table>
            <h4>รายละเอียดการชำระ</h4>
            <table style="width:100%">              
                <tr>
                    <td colspan='1'></td>
                    <td colspan='13'></td>
                    <td colspan='10' style='text-align:left'>จำนวนเงิน(บาท)</td>
                    <td colspan='5'></td>
                </tr>
                <tr>
                    <td colspan='1'></td>
                    <td colspan='13' style='text-align:left'>ยอดเงินต้นคงค้าง</td>
                    <td colspan='10' style='text-align:right'>{{ $order->bill_principal }}</td>
                    <td colspan='5'></td>
                </tr>
                <tr>
                    <td colspan='1'></td>
                    <td colspan='13' style='text-align:left'>ยอดดอกเบี้ยคงค้าง</td>
                    <td colspan='10' style='text-align:right'>{{ $order->bill_interest }}</td>
                    <td colspan='5'></td>
                </tr>
                <tr>
                    <td colspan='1'></td>
                    <td colspan='13' style='text-align:left'>ยอดรวมที่ต้องชำระงวดนี้</td>
                    <td colspan='10' style='text-align:right'>{{ $order->bill_total }}</td>
                    <td colspan='5'></td>
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
                </tr> --}}
                {{-- </tbody> --}}
                @for($i=0; $i < 10 ;$i++)
                <tr>
                    <td colspan='29'></td>
                </tr>
                @endfor
            </table>
            <table style="width:100%;border-collapse: collapse;">
                <tr>
                    <td style='text-align:left;' colspan='29'>หมายเหตุ : ยอดชำระนับถึงวันที่ระบบทำรายการ (อ่าจต่างจากยอดชำระจริง)</td>
                </tr>
                <tr style="background-color:#FF0000; color: white;font-size:14px;"> <td colspan='29' style='text-align:center'>ช่องทางการชำระเงิน และการนำส่งหลักฐานการชำระเงิน</td> <tr>
                <tr>
                    <td colspan='3'></td>
                    <td colspan='5'>ธนาคาร</td>
                    <td colspan='7'>ไทยพาณิชย์ จำกัด (มหาชน)</td>
                    <td colspan='14'></td>
                </tr>
                <tr>
                    <td colspan='3'></td>
                    <td colspan='5'>ชื่อบัญชี</td>
                    <td colspan='7'>บริษัท สยาม เซย์ซอน จำกัด</td>
                    <td colspan='14'>กรุณานำส่งหลักฐานการชำระเงินให้แก่พนักงานขาย บริษัทสยาม</td>
                </tr>
                <tr>
                    <td colspan='3'></td>
                    <td colspan='5'>เลขที่บัญชี</td>
                    <td colspan='7'>111-3-93830-0</td>
                    <td colspan='14'>เซย์ซอน เมื่อท่านทำการชำระเงินเรียบร้อยแล้ว</td>
                </tr>
                <tr>
                    <td colspan='3'></td>
                    <td colspan='5'>ประเภทบัญชี</td>
                    <td colspan='7'>กระแสรายวัน</td>
                    <td colspan='14'></td>
                </tr>
                <tr>
                    <td colspan='29'></td>
                </tr>
                <tr style=""><td colspan='29' style='text-align:center'>กรุณาชำระภายในกำหนดเพื่อหลีกเลี่ยงเบี้ยปรับชำระล่าช้า</td><tr>
            </table>
        </div>
    </body>
</html>