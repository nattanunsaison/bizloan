<x-app-layout>
    @section('title', __('Dealer Account detail'))
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-auto shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div>
                        <table class = "table" id="the-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th rowspan='2'>No</th>
                                    <th rowspan='2'>SSS dealer ID</th>
                                    <th rowspan='2'>Payee ID</th>
                                    <th rowspan='2'>Type</th>
                                    <th colspan='2'>Beneficiary Name</th>
                                    <th rowspan='2'>Address</th>
                                    <th rowspan='2'>Email</th>
                                    <th rowspan='2'>Bank code</th>
                                    <th rowspan='2'>Account number</th>
                                    <th rowspan='2'>Tax ID</th>

                                </tr>
                                <tr>
                                    <th>TH</th>
                                    <th>EN</th>
                                </tr>
                            </thead>
                            <tbody>

                                    <!-- <tr> <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><tr> -->
                                    @foreach($bank_account_details as $key=>$detail)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td class="text"><a href='https://sss.siamsaison.com/dealers/dealer?id={{$detail->dealer_id}}' target='_blank'>{{$detail->dealer_id}}</a></td>
                                            <td>{{$detail->payee_id}}</td>
                                            <td class="text">{{$detail->type}}</td>
                                            <td class="text">{{$detail->beneficiary_name_thai}}</td>
                                            <td class="text">{{$detail->beneficiary_name_english}}</td>
                                            <td class="text">{{$detail->address1}}</td>
                                            <td class="text">{{$detail->email}}</td>
                                            <td class="number">{{$detail->bank_code}}</td>
                                            <td class="number">{{$detail->account_number}}</td>
                                            <td class="number">{{$detail->tax_id}}</td>
                                        </tr>
                                    @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready( function () {
        title = "dealer_payment_history_{{request()->yearmonth}}_{{\Carbon\Carbon::now()->isoFormat('YYYYMMDD-hhmm')}}"
        $('#the-table').DataTable({
            //'ordering':false,
            'order':[[0,'asc']],
			dom: '<"top"Bfl>rt<"bottom"ip><"clear">',
			buttons: [
				{
					extend: 'csvHtml5',
					title: title
				},
				{
					extend: 'excelHtml5',
					title: title
				},
			],
            lengthMenu: [
                [25, 50, 100, -1],
                [25, 50, 100, 'All'],
            ],
        });
        @if(!is_null(request()->contractor))
        var table = $('#the-table').DataTable();
        table.search('{{request()->contractor}}').draw()
        @endif
    } );
</script>
