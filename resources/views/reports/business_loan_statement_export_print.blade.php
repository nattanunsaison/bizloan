<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link href="https://fonts.googleapis.com/css2?family=Trirong:wght@400&family=Trirong:wght@400&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
        <style type='text/css'>
            body {
                font-family: 'browallia';
                height: 842px;
                width: 595px;
                /* to centre page on screen*/
                margin-top:10px;
                margin-left: auto;
                margin-right: auto;
            }
            .bottom-border{
                border-bottom: 1px solid black;
            }
            .top-border{
                border-top: 1px solid black;
            }
            .right-border{
                border-right: 1px solid black;
            }
            .left-border{
                border-left: 1px solid black;
            }
            .top-dashed-border{
                border-top: 1px dashed;
            }
        </style>
    </head>
    <body size='A4'>
    <table style="width:100%">
        <tbody>
            <tr>
                <td colspan='10' rowspan='4'><img src="../images/saison_credit.png" alt='saison_credit_logo' style="float:left;height:90px;"></td>
                <td colspan='19' rowspan='1' style='text-align:right;font-size:20px;'>ใบแจ้งยอดรายการเซย์ซอนเครดิต/ใบแจ้งหนี้</td>                
            </tr>
            <tr>
                <td colspan='19' rowspan='3' style='text-align:right'><img style="float:right;height:40px;" src="{{config('constants.app_url')}}/siamsaisonlogo.png"></td>                
            </tr>
        </tbody>
    </table>
        <div class='content'>
            <br>
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
                    <tr style="color:white"> <td colspan='29'>x</td> <tr>
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
                    <tr>
                        <td colspan='1'></td>
                        <td colspan='3' style='text-align:left;width:40px'>ยอดเงินกู้</td>
                        <td colspan='1' style='text-align:right'>{{ number_format($order->loan_amount,2) }}</td>
                        <td colspan='1' style='text-align:left;width:300px;background-color:red'></td>
                        {{-- <td></td> --}}
                    </tr>
                    <tr>
                        <td colspan='1'></td>
                        <td colspan='3' style='text-align:left'>ระยะเวลา (วัน)</td>
                        <td colspan='1' style='text-align:right'>{{ number_format($order->terms) }}</td>
                        <td colspan='1'></td>
                    </tr>
                    <tr>
                        <td colspan='1'></td>
                        <td colspan='3' style='text-align:left'>อัตราดอกเบี้ย (%)</td>
                        <td colspan='1' style='text-align:right'>{{ number_format($order->interest_rate,2) }}</td>
                        <td colspan='1'></td>
                    </tr>
                    <tr>
                        <td colspan='1'></td>
                        <td colspan='3' style='text-align:left;'>อัตราดอกเบี้ยปรับผิดนัดชำระ (%)</td>
                        <td colspan='1' style='text-align:right'>{{ number_format($order->delay_penalty_rate,2) }}</td>
                        <td colspan='1'></td>
                    </tr>
                    @if ( $order->discount_rate > 0 )
                        <tr>
                            <td colspan='1'></td>
                            <td colspan='3' style='text-align:left'>อัตราส่วนลดดอกเบี้ย (%)</td>
                            <td colspan='1' style='text-align:right'>{{ number_format($order->discount_rate,2) }}</td>
                            <td colspan='1'></td>
                        </tr>
                    @endif
            </table>
            <h4>รายละเอียดการชำระ</h4>
            <table style="width:100%">              
                <tr>
                    <td colspan='1'></td>
                    <td colspan='3'></td>
                    <td colspan='1' style='text-align:left'>จำนวนเงิน(บาท)</td>
                    <td colspan='1'></td>
                </tr>
                <tr>
                    <td colspan='1'></td>
                    <td colspan='3' style='text-align:left'>ยอดเงินต้นคงค้าง</td>
                    <td colspan='1' style='text-align:right'>{{ number_format($order->bill_principal,2) }}</td>
                    <td colspan='1' style='text-align:left;width:300px;background-color:red'></td>
                </tr>
                <tr>
                    <td colspan='1'></td>
                    <td colspan='3' style='text-align:left'>ยอดดอกเบี้ยคงค้าง</td>
                    <td colspan='1' style='text-align:right'>{{ number_format($order->bill_interest,2) }}</td>
                    <td colspan='1'></td>
                </tr>
                <tr>
                    <td colspan='1'></td>
                    <td colspan='3' style='text-align:left'>ยอดรวมที่ต้องชำระงวดนี้</td>
                    <td colspan='1' style='text-align:right'>{{ number_format($order->bill_total,2) }}</td>
                    <td colspan='1'></td>
                </tr>
                @for($i = 0; $i < 8;$i++)
                <tr><td colspan='6' style='color:white'>x</td></tr>
                @endfor
            </table>
            <table style="width:100%;border-collapse: collapse;">
                <tr style="background-color:black"> <td colspan='4'></td> <tr>
                <tr>
                    <td style='text-align:left; font-size:14px;' colspan='4'>หมายเหตุ : ยอดชำระนับถึงวันที่ระบบทำรายการ (อ่าจต่างจากยอดชำระจริง)</td>
                </tr>
                <tr>
                    <td colspan='14'></td>
                </tr>
                <tr style="background-color:red; color: white;"> <td colspan='29' style='text-align:center;color:white' >ช่องทางการชำระเงิน และการนำส่งหลักฐานการชำระเงิน</td> <tr>
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