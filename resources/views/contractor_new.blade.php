<x-app-layout>
    @section('title', __('Suppliers'))
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden text-sm shadow-sm sm:rounded-lg p-2">
                <table class = "table" id='the-table'>
                    <thead class="thead-dark">
                        <tr>
                            <th rowspan ='2'>No. </th>
                            <th rowspan ='2'>Id </th>
                            <th rowspan ='2'>Tax id</th>
                            <th rowspan ='2'>Status</th>
                            <th colspan ='2' class='dt-head-center'>Company name</th>
                            <th rowspan ='2'>Current credit limit</th>
                            <th rowspan ='2'>Purchase amount</th>
                            <th rowspan ='2'>Available balance</th>
                            <th rowspan ='2'>Active dealers</th>
                        </tr>
                        <tr>
                            <th>Th</th>
                            <th>En</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contractors as $contractor)
                            @php 
                                $limit_amount = $contractor->eligibilities()->orderBy('id','desc')->first()->limit_amount;
                                $used_amount = $contractor->orders()->whereNull('paid_up_ymd')->sum('purchase_amount');
                                $balance = $limit_amount - $used_amount;
                            @endphp
                            <tr>
                                <td>
                                    {{$loop->index + 1}}    
                                </td>
                                <td><a href="https://sss.siamsaison.com/contractors/contractor?id={{$contractor->id}}" target="_blank">{{$contractor['id']}}</a></td>
                                <td>{{$contractor['tax_id']}}</td>
                                <td>{{$contractor->status->labels()}}</td>
                                <td class="text">{{$contractor->th_company_name}}</td>
                                <td class="text">{{$contractor->en_company_name}}</td>
                                <td class="number">{{number_format($limit_amount)}}</td>
                                <td>{{number_format($used_amount)}}</td>
                                <td class="number">{{number_format($balance)}}</td>                  
                                <td>{{number_format($contractor->orders()->pluck('dealer_id')->unique()->count())}}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script>
        $(document).ready( function () {
            $('#the-table').DataTable({
                'dom': '<"top"Bif>rt<"bottom"lp><"clear">',
                'order':[[0,'asc']],
                'paging': true,
                'lengthMenu':[[25,50,100,500,-1],[25,50,100,500,'All']],
                "columnDefs": [
                    { "orderable": false, "targets": 0 },
                    { "orderable": false, "targets": 1 },
                    { "orderable": false, "targets": 2 },
                    { "orderable": false, "targets": 3 },
                    { "orderable": false, "targets": 4 },
                    { "orderable": false, "targets": 5 },
                    { targets: '_all', className: 'dt-head-center'},
                    { targets: [6,7,8,9], className: 'dt-body-right'},
                ],
                buttons: [
                    {
                        extend: 'csvHtml5',
                        title: "supplier_list_-{{\Carbon\Carbon::now()->isoFormat('YYYYMMDD-hhmm')}}"
                    },
                    {
                        extend: 'excelHtml5',
                        title: "supplier_list_-{{\Carbon\Carbon::now()->isoFormat('YYYYMMDD-hhmm')}}"
                    },
                ]
            });
        });

        var table = $('#example').DataTable();
</script>
