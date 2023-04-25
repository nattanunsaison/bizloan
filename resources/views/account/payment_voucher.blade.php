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
                        @for($i=0;$i < 29;$i++) <td style='color:white'>x</td> @endfor 
                    </tr>
                    <tr>
                        @for($i=0;$i < 29;$i++) <td style='color:white'>x</td> @endfor
                    </tr>
                    <tr>
                        @for($i=0;$i < 29;$i++) <td style='color:white'>x</td> @endfor
                    </tr>
                    <tr>
                        @for($i=0;$i < 29;$i++) <td style='color:white'>x</td> @endfor
                    </tr>
                    <tr>
                        @for($i=0;$i < 29;$i++) <td style='color:white'>x</td> @endfor
                    </tr>
                    </tbody>
            </table>

            <table style="width:100%">
                <thead>
                    <tr>
                        <th colspan='29' style='text-align:left;font-size:15px'><b>Payment voucher</b></th>
                    </tr>
                    <tr>
                        <th colspan='29' style='text-align:left;font-size:15px'><b>Account team</b></th>
                    </tr>
                </thead>
            </table>

            @php 
                $contracor = $record->order->contrator;
                $repayment_ymd = \Carbon\Carbon::parse($record->receive_ymd)->isoFormat('D MMMM YYYY');
            @endphp
            
            <table style="width:100%">
                <tbody>
                    <tr>
                        @for($i=0;$i < 17;$i++) <td style='color:white'>x</td> @endfor
                        <td colspan='6' style='text-align:left'>Reference PO No.</td>
                        <td style='text-align:left'>receipt_no</td>
                    </tr>
                    <tr>
                        @for($i=0;$i < 17;$i++) <td style='color:white'>x</td> @endfor
                        <td colspan='6' style='text-align:left'>PV No.</td>
                        <td style='text-align:left'>receipt_no</td>
                    </tr>
                    <tr>
                        @for($i=0;$i < 17;$i++) <td style='color:white'>x</td> @endfor
                        <td colspan='6' style='text-align:left'>Date</td>
                        <td style='text-align:left'>{{$repayment_ymd}}</td>
                    </tr>

                </tbody>
            </table>
            
            <table style="width:100%">
                <thead>
                    <tr>
                        <th colspan='24' style='text-align:center'>DESCRIPTION</th>
                        <th colspan='5' style='text-align:center'>AMOUNT</th>
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
                    <td colspan='24'>Payment back to Supply Chain Finance Supplier({{$record->order->dealer->en_dealer_name}})</td>
                    <td colspan='5' style='text-align:right'>{{$record->receive_amount}}</td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align:center'></td>
                    <td colspan='15'>(1) Receive amount from Buyer({{$record->order->contractor->en_company_name}}) </td>
                    <td colspan='7' style='color:white'>x</td>
                    <td colspan='5' style='text-align:right'>{{$record->receive_amount}}</td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align:center'></td>
                    <td colspan='22'>(2) Amount purchased by Supplier</td>
                    <td colspan='5' style='text-align:right'>{{$record->receive_amount*0.9}}</td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align:center'></td>
                    <td colspan='22'>(3) Amount Interest</td>
                    <td colspan='5' style='text-align:right'>{{$record->receive_amount_detail->paid_interest}}</td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align:center'></td>
                    <td colspan='22'>(4) Deduct amount WHT 1%</td>
                    <td colspan='5' style='text-align:right'>{{$record->receive_amount_detail->paid_tax}}</td>
                </tr>
                @for($i=0;$i < 10;$i++)
                <tr>
                    <td colspan='2' style='color:white'>x</td>
                    <td colspan='22'style='color:white'>x</td>
                    <td colspan='5' style='color:white'>x</td>
                </tr>
                @endfor
                <tr>
                    <td colspan='2'></td>
                    <td colspan='15' style='color:white'>x</td>
                    <td colspan='7'>Total</td>
                    <td colspan='5' style='text-align:right'>{{$record->receive_amount_detail->payback_amount}}</td>
                </tr>
                <tr>
                    <td colspan='2'></td>
                    <td colspan='15' style='color:white'>x</td>
                    <td colspan='7'>VAT 7%</td>
                    <td colspan='5' style='text-align:right'>0.00</td>
                </tr>
                <tr>
                    <td colspan='2'></td>
                    <td colspan='15' style='color:white'>x</td>
                    <td colspan='7'>WHT 3%</td>
                    <td colspan='5' style='text-align:right'>0.00</td>
                </tr>
                <tr>
                    <td colspan='2'></td>
                    <td colspan='15' style='color:white'>x</td>
                    <td colspan='7'>Payback amount</td>
                    <td colspan='5' style='text-align:right'>{{$record->receive_amount_detail->payback_amount}}</td>
                </tr>
                </tbody>
            </table>
            
            <table style="width:100%">
                <thead>
                    <tr>
                        <th colspan='7' style='text-align:center'>Accounting code</th>
                        <th colspan='7' style='text-align:center'>Description</th>
                        <th colspan='7' style='text-align:center'>Debit</th>
                        <th colspan='8' style='text-align:center'>Credit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan='7' style='text-align:center'>201001</td>
                        <td colspan='7'>AP payable</td>
                        <td colspan='7' style='text-align:right'>{{$record->receive_amount*0.1}}</td>
                        <td colspan='8'></td>
                    </tr>
                    <tr>
                        <td colspan='7' style='text-align:center'>103004</td>
                        <td colspan='7'>Prepaid income tax</td>
                        <td colspan='7' style='text-align:right'>{{$record->receive_amount_detail->paid_tax}}</td>
                        <td colspan='8'></td>
                    </tr>
                    <tr>
                        <td colspan='7' style='text-align:center'>401002</td>
                        <td colspan='7'>Interest income</td>
                        <td colspan='7'></td>
                        <td colspan='8' style='text-align:right'>{{$record->receive_amount_detail->interest}}</td>
                    </tr>
                    <tr>
                        <td colspan='7' style='text-align:center'>101201</td>
                        <td colspan='7'>SCB saving account</td>
                        <td colspan='7'></td>
                        <td colspan='8' style='text-align:right'>{{$record->receive_amount_detail->payback_amount}}</td>
                    </tr>
                </tbody>
            </table>
            {{--Payment information--}}
            <table style="width:100%">
                <thead>
                    <tr>
                        <th colspan='29' style='text-align:left;font-size:15px'><b>Payment information</b></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan='7'>Payee</td>
                        <td colspan='22'>{{$record->order->dealer->en_dealer_name}}</td>
                    </tr>
                    <tr>
                        <td colspan='7'>Payment method</td>
                        <td colspan='22'>T/T</td>
                    </tr>
                    <tr>
                        <td colspan='7'>Date</td>
                        <td colspan='22'>{{$record->receive_ymd}}</td>
                    </tr>
                    <tr>
                        <td colspan='7'>Bank</td>
                        <td colspan='22'>xxxx</td>
                    </tr>
                </tbody>
            </table>
            {{--Signature--}}
            <table style="width:100%;border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td colspan='10' style='text-align:center'><b>Authorized by</b></td>
                        <td colspan='10' style='text-align:center'><b>Reviewed by</b></td>
                        <td colspan='9' style='text-align:center'><b>Created by</b></td>
                    </tr>
                    <tr style='height:50px'>
                        <td colspan='10' style='text-align:center'>x</td>
                        <td colspan='10' style='text-align:center'>x</td>
                        <td colspan='9' style='text-align:center'>{{auth()->user()->name}}</td>
                    </tr>
                    <tr>
                        <td colspan='10' style='text-align:center'>Date:</td>
                        <td colspan='10' style='text-align:center'>Date:</td>
                        <td colspan='9' style='text-align:center'>Date: {{\Carbon\Carbon::now()->isoFormat('DD MMM YYYY')}}</td>
                    </tr>
                </tbody>
            </table>
            
            @if(request()->has('mode'))
            <button class='button' onclick="window.location='{{url('download/payment_voucher?id=')}}{{$record->id}}'">Export this voucher</button>
            @endif
        </div>
    </body>
</html>