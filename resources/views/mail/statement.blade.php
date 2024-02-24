<html>
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Trirong:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    </head>
    <style>
        body {
            font-family: 'Nunito','Trirong','Leelawadee';
            line-height: 1.15;
            padding-top: 10px;
        }
    </style>
<body>
เรียน {{$order->customer?->th_company_name}}<br>
<br>
บริษัท สยาม เซย์ซอน จำกัด ขอเรียนแจ้งให้ท่านได้ทราบว่า หนังสือเบิกใช้เงินกู้ เลขที่ {{$order->order_number}} ได้รับการอนุมัติแล้ว
<br>
ทางบริษัทจะดำเนินการโอนเงินให้ท่านตามวันที่ระบุไว้ในหนังสือเบิกใช้เงินกู้ ดังรายละเอียดด้านล่าง
<br>
<br>
<span><u><b>รายละเอียดสัญญา</b></u></span><br>
วันที่โอนเงิน: {{\Carbon\Carbon::parse($order->purchase_ymd)->addYears(543)->locale('th_TH')->isoFormat('LL')}}
<br>
วันที่ครบกำหนดสัญญา: {{\Carbon\Carbon::parse($order->installments?->first()->due_ymd)->addYears(543)->locale('th_TH')->isoFormat('LL')}}
<br>
ยอดเงินกู้: {{number_format($order->purchase_amount)}}
<br>
ระยะเวลา: {{$order->product_offering?->product->terms}} วัน
<br>
อัตราดอกเบี้ย: {{number_format($order->product_offering?->interest_rate - $order->product_offering?->discount_rate)}}%
<br>
อัตราเบี้ยปรับผิดนัดชำระ: {{$order->product_offering?->delay_penalty_rate}}%
<br>
<br>
หากมีข้อสงสัยกรุณาติดต่อเจ้าหน้าที่ของบริษัทในเวลาทำการที่หมายเลข โทรศัพท์ 02-096-3121 หรือ อีเมล info@siamsaison.com
(จันทร์ - ศุกร์ 09.00 - 17.00 ยกเว้นวันหยุดนักขัตฤกษ์)<br>
<br>
<br>
ขอแสดงความนับถือ<br>
บริษัท สยาม เซย์ซอน จำกัด<br>
</body>
</html>