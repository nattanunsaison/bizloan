<x-app-layout>
    @section('title', __('Buyers'))
    <head>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
		<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
		<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    </head>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-auto text-sm shadow-sm sm:rounded-lg p-2">
                <div>
                    <table class = "table table-bordered" id="the-table" style="width:100%">
                        <thead class="thead-dark text-center border">
                            <tr class='text-center'>
                                <th rowspan='2' class='text-center'>No. </th>
                                <th rowspan='2'>Id </th>
                                <th rowspan='2'>Tax id</th>
                                <th colspan='2' class='dt-head-center'>Company name</th>
                                <th rowspan='2' class='text-center'>Area</th>
                                <th rowspan='2' class='text-center'>Bank account</th>
                                <th rowspan='2'>Registered at</th>
                                <th rowspan='2'>Net input amount[THB]</th>
                                <th rowspan='2'>Purchasing contractors</th>
                            </tr>
                            <tr>
                                <th class='text-center'>TH</th>
                                <th class='text-center'>EN</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($dealers as $key=>$dealer) 
                                @php 
                                    $id = $dealer['id'];
                                @endphp                       
                                <tr>
                                    <td >{{$loop->index+1}}</td>
                                    <td ><a href="https://sss.siamsaison.com/dealers/dealer?id={{$id}}" target="_blank">{{$id}}</a></td>
                                    <td class="text">{{$dealer['tax_id']}}</td>
                                    <td class="text">{{$dealer['dealer_name']}}</td>
                                    <td class="text">{{$dealer['en_dealer_name']}}</td>
                                    <td class="text">{{$dealer['area_id']}}</td>
                                    <td class="text">{{$dealer['bank_account']}}</td>
                                    <td class="text">{{\Carbon\Carbon::parse($dealer['created_at'])->isoFormat('DD MMMM YYYY')}}</td>
                                    <td class="number">{{number_format($dealer->orders->sum('purchase_amount'),2)}}</td>
                                    <td class="number">{{number_format($dealer->orders->pluck('contractor_id')->unique('contractor_id')->count())}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    $(document).ready( function () {
        $('#the-table').DataTable({
            'order':[[0,'asc']],
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100,"All"] ],
            "columnDefs": [
                { "orderable": true, "targets": 9 },
                { "orderable": false, "targets": '_all' },
                { targets: [1,2,3,4,5,6,7,8,9], className: 'dt-body-right'},
                { targets: [1,2,3,4,5,6,7,8,9], className: 'dt-head-center'},
                /*{ "orderable": false, "targets": 12 },
                { "orderable": false, "targets": 13 },
                { "orderable": false, "targets": 14 },*/
            ],
			//dom: 'Blfrtip',
			dom: '<"top"Bfl>rt<"bottom"ip><"clear">',
			buttons: [
				{
					extend: 'csvHtml5',
					title: "dealer_summary_{{\Carbon\Carbon::now()->isoFormat('YYYYMMDD-hhmm')}}"
				},
				{
					extend: 'excelHtml5',
					title: "dealer_summary_{{\Carbon\Carbon::now()->isoFormat('YYYYMMDD-hhmm')}}"
				},
			]
        });
    } );
</script>
