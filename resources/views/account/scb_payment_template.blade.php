<table>
    <tr>
        <th>No.</th>
        <th>Payee ID(รหัสผู้รับเงิน)</th>
        <th>Account name (ชื่อผู้รับเงิน)</th>
        <th>Account no (เลขที่บัญชี)</th>
        <th>Amount (จำนวนเงิน)</th>
        <th>Bank code (รหัสธนาคาร)</th>
        <th>Fax/SMS/Email (บริการเสริม)</th>
        <th>Beneficiary charge (หักค่าธรรมเนียมจากผู้รับ)</th>
        <th>Customer reference (20)</th>
        <th>Remark (50) Print in credit advice</th>
    </tr>
    <tr>
        <td>{{1}}</td>
        @php 
        $scb_account = $record->order->dealer->scb_account
        @endphp
        <td>{{$scb_account ? $scb_account->payee_id : 'not found'}}</td>
        <td>{{$scb_account ? $scb_account->beneficiary_name_thai : 'not found'}}</td>
        <td>{{$scb_account ? $scb_account->account_number : 'not found' }}</td>
        <td>{{$record->net_pay_amount}}</td>
        <td>{{$scb_account ? $scb_account->back_code : 'not found'}}</td>
        <td>{{$scb_account ? $scb_account->email :  'not found'}}</td>
        <td>{{$scb_account ? $scb_account->ben_charge :  'not found'}}</td>
        <td></td>
        <td></td>
    </tr>
</table>