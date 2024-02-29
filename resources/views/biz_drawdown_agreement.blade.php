<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css2?family=Trirong:wght@400&family=Trirong:wght@400&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400&family=Nunito:wght@400&display=swap" rel="stylesheet">
        <title>Bizloan drawdown</title>

        <style>
            .list-outside	{
                list-style-position: outside;
            }
            ol {
            counter-reset: count;
            }
            ol > li {
            counter-increment: count;
            }
            ol > li::marker {
                content: counters(count, ".", decimal) ". "  ;
            }
            ol.main > ol > li.main:before {
                content: attr(seq) ".";
                counter-increment: second_level;
            }
            
            body {
                font-family: 'Nunito','Trirong';
                /*height: 842px;
                width: 595px;*/
                /* to centre page on screen*/
                font-size: 15px;
                width: 21cm;
                /*height: 29.7cm;*/
                /*box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);*/
                align: center;
                margin: 10px auto;
                list-style-position: outside;
            }
            @counter-style thai_char {
                system: fixed;
                symbols: ก ข ค ง จ ฉ ช ซ ฌ;
                suffix: ". ";
            }
            .consonants {
                list-style: thai_char;
                /* margin-right: 10px; */
            }
            @page{
                margin-top: 50px;
                margin-left: 5px;
                margin-right: 5px;
                margin-bottom: 25px;
                @top-right {
                    content: "Page " counter(pageNumber);
                }
            }
            @page :first{
                margin-top: 1cm;
            }
            @counter-style number_dot_number {
                system: fixed;
                symbols: 'd' 'e' 'f' '4' '5' '6' '7' '8' '9';
                suffix: ". A" counter(item);
            }
            .grid-container {
                display: grid;
                grid: 150px / auto auto;
                grid-gap: 10px;
                padding: 10px;
            }
            /* ol > ul > li {
                margin-left: -40px
            }
            ol > ol > li {
                margin-left: -20px
            }  */
            .tab {
                display: inline-block;
                margin-left: 40px;
            }
            @media print {
                footer {
                    position: fixed;
                    bottom: 0px;
                    font-size: 8px;
                    color:gray;
                }

            }

        </style>
    </head>
    <body class="antialiased">
		<div style="padding-top: 30px;padding-right: 30px;padding-bottom: 50px;padding-left: 30px;">
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

			<h2 align="center">หนังสือเบิกใช้เงินกู้ </h2>
            หนังสือเบิกใช้เงินกู้ฉบับนี้ (<strong>“หนังสือฯ”</strong>) ทำขึ้นในวันที่ [ใส่วันที่] โดย
            <br>
            <strong>โดยที่</strong> ผู้กู้ประสงค์จะกู้ยืมเงินจากบริษัท สยามเซย์ซอน จำกัด (ซึ่งต่อไปในสัญญาฯ เรียกว่า <strong>“บริษัทฯ”</strong>) ผ่านวิธีการเบิกใช้เงินกู้โดยผู้กู้เป็นครั้งคราว โดยผู้กู้และบริษัทฯ ได้เข้าทำสัญญาข้อกำหนดและเงื่อนไขเงินกู้ ลงวันที่ [ใส่วันที่] (<strong>“สัญญาข้อกำหนดและเงื่อนไขเงินกู้”</strong>) เพื่อตกลงข้อกำหนดและเงื่อนไขในการกู้ยืมเงิน โดยภายใต้สัญญาข้อกำหนดและเงื่อนไขเงินกู้ ผู้กู้ตกลงที่จะเบิกใช้เงินกู้ตามตามแบบและวิธีการที่บริษัทฯ กำหนดเป็นครั้งคราว
            <ol class='main list-outside'>
                <li>ทั่วไป</li>
                <ol>
                    <li>หนังสือฯ ฉบับนี้ให้ถือเป็นส่วนหนึ่งของสัญญาข้อกำหนดและเงื่อนไขเงินกู้ โดยข้อตกลงและเงื่อนไขในเรื่องใดที่มิได้กำหนดไว้ภายใต้หนังสือฯ ฉบับนี้ ให้นำข้อสัญญาในเรื่องดังกล่าวภายใต้สัญญาข้อกำหนดและเงื่อนไขเงินกู้มาบังคับใช้</li>
                    <li>เว้นแต่จะนิยามไว้เป็นอย่างอื่นภายใต้หนังสือฯ ฉบับนี้ ให้คำหรือความหมายใด ๆ ภายใต้หนังสือฯ ฉบับนี้มีความหมายเดียวกับความหมายที่กำหนดไว้ภายใต้สัญญาข้อกำหนดและเงื่อนไขเงินกู้ </li>
                </ol>
                <li>จำนวนเงินกู้</li>
                ผู้กู้ขอเบิกใช้เงินกู้จากบริษัทฯ เป็นจำนวน [ใส่จำนวนเงินกู้] บาท [เป็นระยะเวลา [ใส่ระยะเวลาชำระเงินกู้] วันนับแต่วันที่ผู้กู้ได้รับเงินกู้ตามข้อ 4]

                <li>ระยะเวลาชำระคืนเงินกู้</li>
                <ol>
                    <li>ผู้กู้ตกลงชำระคืนเงินกู้ให้แก่บริษัทฯ เป็นงวดราย [ใส่ระยะเวลาของงวด] จำนวน [ใส่จำนวนงวด] งวด งวดละ [จำนวนที่ต้องชำระคืนต่องวด] บาท ทุก ๆ วันที่ [ใส่วันที่กำหนดชำระเงินกู้] โดยเริ่มชำระงวดแรกตั้งแต่เดือน [ใส่เดือนของการชำระงวดแรก]</li>
                    <li>ผู้กู้ตกลงชำระคืนเงินกู้ทั้งหมดให้แก่บริษัทฯ ในคราวเดียว ภายในวันที่ [ใส่วันที่กำหนดชำระคืนเงินกู้]</li>
                </ol>

                <li>วิธีการโอนเงินตามคำขอเบิกใช้เงินกู้และวันได้รับเงินกู้</li>
                ผู้กู้ตกลงให้บริษัทฯ ทำการโอนเงินผ่านระบบเข้าบัญชีและภายในกำหนดระยะเวลาที่ได้กำหนด โดยผู้กู้ขอรับรองและรับผิดชอบตามที่บริษัทฯ ได้ดำเนินการตามประสงค์ของผู้กู้ดังกล่าวข้างต้นทุกประการ ทั้งนี้ให้ถือว่าผู้กู้ได้รับเงินแล้วอย่างครบถ้วนตามรายละเอียดบัญชี จำนวนและวันที่ได้ระบุในเอกสารแนบท้ายสัญญาฯฉบับนี้
                <br>
                <table style="width:80%">
                    <tbody>
                        <tr>
                            <th style='text-align:left'><i><strong>ธนาคาร</strong></i></th>
                            <td><i>ธนาคารไทยพาณิชย์ จำกัด (มหาชน)</i></td>
                        </tr>
                        <tr>
                            <th style='text-align:left'><i><strong>ชื่อบัญชี</strong></i></th>
                            <td><i>{{$order->customer->th_company_name}}</i></td>
                        </tr>
                        <tr>
                            <th style='text-align:left'><i><strong>ประเภทบัญชี</strong></i></th>
                            <td><i>กระแสรายวัน</i></td>
                        </tr>
                        <tr>
                            <th style='text-align:left'><i><strong>สำนักงานใหญ่/สาขา</strong></i></th>
                            <td><i> ถนนเสรีไทย (สวนสยาม)</i></td>
                        </tr>
                        <tr>
                            <th style='text-align:left'><i><strong>เลขที่บัญชี</strong></i></th>
                            <td><i> 109-3023275</i></td>
                        </tr>
                    </tbody>
                </table>
                <li>ดอกเบี้ยเงินกู้</li>
                <ol>
                    <li class='main'>ผู้กู้ตกลงชำระดอกเบี้ยเงินกู้ในอัตรา [ใส่อัตราดอกเบี้ย] บาทต่อปี ให้แก่บริษัทฯ </li>
                    <li class='main'>ผู้กู้ตกลงว่าในกรณีที่บริษัทฯ คิดดอกเบี้ยในอัตราที่ต่ำกว่าอัตราที่กำหนดในข้อ 4.1 ของสัญญาข้อกำหนดและเงื่อนไขเงินกู้เป็นครั้งคราวตามดุลพินิจของบริษัทฯ ซึ่งรวมถึงการคิดดอกเบี้ยในอัตราที่ระบุในข้อ 5.1 ข้างต้น การคิดดอกเบี้ยในอัตราดังกล่าวจะไม่เป็นการเปลี่ยนหรือจำกัดสิทธิของบริษัทฯ ในการคิดดอกเบี้ยในอัตราที่กำหนดตามข้อ 4.1 ของสัญญาข้อกำหนดและเงื่อนไขเงินกู้แต่อย่างใด</li>
                </ol>

                <li>ค่าธรรมเนียมและค่าใช้จ่าย </li>
                [ผู้กู้ตกลงชำระค่าธรรมเนียมเงินกู้ให้แก่บริษัทฯ ในอัตรา [ใส่อัตราค่าธรรมเนียมเงินกู้]] 

                <li>7.	หลักประกัน</li>
                [ผู้กู้ตกลงวางหลักประกันเงินกู้ให้แก่บริษัทฯ ตามเอกสารแนบท้าย (ถ้ามี)]
                
                หนังสือฯ นี้ทำขึ้นเป็นสอง (2) ฉบับ มีข้อความถูกต้องตรงกัน แต่ละฉบับให้ถือเป็นต้นฉบับ โดยผู้กู้ได้อ่าน เข้าใจ และเห็นชอบข้อความซึ่งได้ระบุไว้ในนี้ด้วยดีโดยตลอดแล้ว จึงได้ลงลายมือชื่อและประทับตราสำคัญของบริษัทฯ (ถ้ามีข้อกำหนดไว้) ต่อหน้าพยานในวันที่ตามที่ได้ระบุไว้ข้างต้น
                <br>
                <p style='text-align:center'>(ส่วนนี้เว้นไว้เพื่อให้คู่สัญญาลงนามในหน้าถัดไป)</p>
                <div class='grid-container'>
                    <div>
                        ผู้กู้:
                        <br>
                        <br>
                        เพื่อและในนามของ:
                        <br>
                        {{$order->customer->th_company_name}}
                        <br>
                        <br>
                        <br>
                        โดย: ____________________________
                        <br>
                        ชื่อ: นายคริสโตเฟอร์ ลีมันตะระ
                        <br>
                        ตำแหน่ง:	 กรรมการ
                        <br>
                        <br>
                        <br>
                        พยาน
                        <br>
                        <br>
                        <br>
                        ___________________________
                        <br>ชื่อ : 
                    </div>
                </div>

            </ol>
        </div>
        <footer>
            bizloan drawdown format
        </footer>
    </body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        //window.print()
    }); 
</script>
