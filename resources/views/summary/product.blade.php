
<x-busloanapp-layout>
    @section('title', 'Products')
    
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-scroll text-sm shadow-sm sm:rounded-lg p-2">
                <table id="table_id" class="display table-auto">
                    <thead>
                        <tr>
                            <th>Product code</th>
                            <th>Product name</th>
                            <th>Term (days)</th>
                            <th>Loan amount (THB)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $key => $product)
                        <tr>
                            <td>{{$product->product_code}}</td>
                            <td>{{$product->product_name}}</td>
                            <td>{{$product->terms}}</td>
                            <td>{{number_format($product->loan_amount)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-busloanapp-layout>

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
    $(document).ready(function() {
        now = "{{\Carbon\Carbon::now()->isoFormat('YYYYMMMDD-HHmm')}}"
        title = "SCF-order_list-"+now
        var table = $('#table_id').DataTable({
                // responsive: true,
                order: [[ 0, "asc" ]],
                "columnDefs": [
                    { targets: [0,1], className: 'dt-body-left'},
                    { targets: [2,3], className: 'dt-body-right'},
                ],
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
            });
    });


</script>