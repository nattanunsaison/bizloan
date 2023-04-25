<x-app-layout>
    @section('title', __('Unpaid order'))

    <div class="py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-auto text-sm shadow-sm sm:rounded-lg p-2">
                <table id="table_id" class="display">
                    <thead>
                        <tr>
                            <th>Order number</th> {{--0--}}
                            <th>Buyer</th>{{--1--}}
                            <th>Seller</th>{{--2--}}
                            <th>Purchase date</th>{{--3--}}
                            <th>Input date</th>{{--4--}}
                            <th>Due date</th>{{--5--}}
                            <th>Amount</th>{{--6--}}
                            <th>Receive amount</th>{{--7--}}
                            <th>Receive date</th>{{--8--}}
                            <th>Interest billing</th>{{--9--}}
                            <th>Delay penalty billing</th>{{--10--}}
                            <th>Interest paid</th>{{--11--}}
                            <th>Delay penalty paid</th>{{--12--}}
                            <th>Outstanding principal</th>{{--13--}}
                            <th>Payback to supplier amount</th>{{--14--}}
                            <th>Occurred exceeded amount</th>{{--15--}}
                            <th>Tax</th>{{--16--}}
                            <th>Paid tax</th>{{--17--}}
                            <th>Comment</th>{{--18--}}
                            <th>Outstanding balance</th>{{--19--}}
                            <th>10%</th>{{--20--}}
                            <th>Order ID</th>{{--21--}}
                            <th>Deleted at</th>{{--22--}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                        @php 
                            $order = $record->order;
                            $detail = $record->receive_amount_detail()->withTrashed()->first();
                            $ten_percent = $order->purchase_amount*0.1;
                            $ten_percent_two_decimal = floor($ten_percent*100)/100;
                        @endphp
                        <tr>
                            <td>{{$order->order_number}}</td>
                            <td>{{$order->contractor->en_company_name}}</td>
                            <td>{{$order->dealer->en_dealer_name}}</td>
                            <td class='text-center'>{{$order->purchase_ymd}}</td>
                            <td class='text-center'>{{$order->input_ymd}}</td>
                            <td class='text-center'>{{$order->installments->first()->due_ymd}}</td>
                            <td class='text-right'>{{number_format($order->purchase_amount,2)}}</td>
                            <td class='text-right'>{{number_format($record->receive_amount,2)}}</td>
                            <td class='text-right'>{{\Carbon\Carbon::parse($record->receive_ymd)->isoFormat('YYYYMMDD')}}</td>
                            <td class='text-right'>{{number_format($order->installments->first()->delayPenalty()['daily_interest'],2)}}</td>
                            <td class='text-right'>{{number_format($order->installments->first()->delayPenalty()['delay_penalty'],2)}}</td>
                            <td>{{number_format($detail->paid_interest,2)}}</td>
                            <td>{{number_format($detail->paid_late_charge,2)}}</td>
                            <td>Outstanding principal</td>
                            <td>{{number_format($detail->payback_amount,2)}}</td>
                            <td>{{number_format($detail->exceeded_amount,2)}}</td>
                            <td>{{number_format($detail->tax,2)}}</td>
                            <td>{{number_format($detail->paid_tax,2)}}</td>
                            <td>{{$record->comment}}</td>
                            <td>{{number_format($detail->outstanding_balance,2)}}</td>
                            <td>{{number_format($ten_percent_two_decimal,2)}}</td>
                            <td>{{$record->order_id}}</td>
                            <td>{{$record->deleted_at}}</td>
                            <!-- <td class='text-right'>
                                <x-button onclick="rowDetail()">See detail</x-button> 
                            </td> -->
                        </tr>

                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
<x-success-svg>Receive record has been deleted!</x-success-svg>
<x-processing></x-processing>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/plug-ins/1.13.4/api/sum().js"></script>

<!-- Main modal -->
<div id="defaultModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative w-full h-full max-w-4xl md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Receive amount detail
                    </h3>
                    <button onclick="closeModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6 w-full text-sm">
                    <div class="grid grid-cols-4 gap-1">
                        <!-- Order -->
                        <div class='font-bold'>Order ID</div>
                        <div class='col-span-3' id='order_id'>Order ID</div>
                        <!-- Order -->
                        <div class='font-bold'>Order number</div>
                        <div class='col-span-3' id='order_number'>Order number</div>
                        <!-- Buyer -->
                        <div class='font-bold'>Buyer</div>
                        <div class='col-span-3' id='buyer'>Buyer</div>
                        <!-- Seller -->
                        <div class='font-bold'>Seller</div>
                        <div class='col-span-3' id='seller'>Seller</div>
                        <!-- Input date -->
                        <div class='font-bold'>Input date</div>
                        <div class='col-span-3' id='input_date'>Input date</div>
                        <!-- Due date -->
                        <div class='font-bold'>Due date</div>
                        <div class='col-span-3' id='due_date'>Due date</div>
                        <!-- Receive date-->
                        <div class='font-bold'>Receive date</div>
                        <div class='col-span-3' id='receive_date'>Receive date</div>
                        <!--  Receive comment -->
                        <div class='font-bold'>Received comment</div>
                        <div class='col-span-3' id='receive_comment'>xxx</div>
                        <!--  Purchase amount -->
                        <div class='font-bold '>Amount</div>
                        <div class='text-xl text-right' id='purchase_amount'>xxx</div>
                        <!--  Receive amount -->
                        <div class='font-bold border-l-4 border-indigo-500'>Receive amount</div>
                        <div class='col-span-1 text-xl text-right' id='receive_amount'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-4">
                        <!--  Ten percent amount -->
                        <div class='font-bold '>Amount 10%</div>
                        <div class='col-span-1 text-xl text-right' id='ten_percent_amount'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-4">
                        <div class='font-bold'>Interest</div>
                        <div class='col-span-1 text-xl text-right' id='interest'>xxx</div>
                        <div class='font-bold border-l-4 border-indigo-500'>Paid interest</div>
                        <div class='col-span-1 text-xl text-right' id='paid_interest'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-4">
                        <div class='font-bold'>Tax</div>
                        <div class='col-span-1 text-xl text-right' id='tax'>xx</div>
                        <div class='font-bold border-l-4 border-indigo-500'>Paid tax</div>
                        <div class='col-span-1 text-xl text-right' id='paid_tax'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-4">
                        <div class='font-bold'>Delay penalty</div>
                        @if(count($records) > 0)
                        <div class='col-span-1 text-xl text-red-700 text-right' id='delay_penalty'>{{number_format($order->installments->first()->delayPenalty()['delay_penalty'],2)}}</div>
                        @endif
                        <div class='font-bold border-l-4 border-indigo-500'>Paid delay penalty</div>
                        <div class='col-span-1 text-xl text-right' id='paid_delay_penalty'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-4">
                        <div class='font-bold'>Payback amount to Supplier</div>
                        <div class='col-span-3 text-xl text-right' id='payback_amount_to_supplier'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-4">
                        <div class='font-bold'>Outstanding balance</div>
                        <div class='col-span-3 text-xl text-right' id='outstanding_balance'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-4">
                        <div class='font-bold'>Exceeded amount</div>
                        <div class='col-span-3 text-xl text-right' id='exceeded_amount'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-4">
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-4">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button onclick="deleteReceiveHistory()" type="button" class="text-white bg-red-400 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-red-200 text-sm font-medium px-5 py-2.5  focus:z-10 dark:bg-red-700 dark:text-red-300 dark:border-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-600">Delete</button>
                    <button onclick="closeModal()" type="button" class="text-white bg-gray-400 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5  focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<x-alert>Receive amount cannot be 0!</x-alert>

<script>
    const targetEl = document.getElementById('defaultModal');
    const modal = new Modal(targetEl);

    function closeModal(){
        modal.toggle();
    }

    function deleteReceiveHistory(){
        order_id = $('#order_id').text()
        confirmDelete(order_id)
    }

    function confirmDelete(id){
        modal.hide()
        $("[id='final-confirm-button']").remove()
        getReceiveHistory(id)
        delete_latest_button = `<button id="final-confirm-button" type="button" onclick='confirmDeleteLatestReceiveHistory(${id})' class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-400 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Delete only latest history</button>`
        delete_all_button = `<button id="final-confirm-button" type="button" onclick='confirmDeleteAllReceiveHistory(${id})' class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-400 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Delete all histories</button>`
        $('#alert-footer').append(delete_latest_button).append(delete_all_button)
        alert_modal.show();     
    }

    function getReceiveHistory(id){
        modal.hide()
        processing_modal.show()
        $.ajax({
            type: 'get',
            url: '/receive_history?order_id='+id,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
                processing_modal.hide()
                //console.log(data)
                list = "<ol class='list-decimal'>";
                count = 0;
                data.forEach((history,key)=>{
                    if(key=== data.length - 1)
                        list = list+`<li>Amount: ${history['receive_amount_format']},Date: ${history['receive_ymd']} (Latest)</li>`
                    else 
                        list = list+`<li>Amount: ${history['receive_amount_format']},Date: ${history['receive_ymd']}</li>`
                    count += 1
                })
                ol = list+'</ol>'
                delete_reason = `<textarea id="delete_reason" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Delete reasons..."></textarea>`
                //console.log('<ol class='decimal'>'+list+'</ol>')
                html = `${delete_reason} There are ${count} receive histories! ${list} This action cannot be undone!`
                $('#general_alert_content').html(html)
                //window.location = '{{url('/unpaidup_orders')}}'
            },
            error: function(err){
                console.log(err)
            }
        })
    }

    function confirmDeleteLatestReceiveHistory(id){
        if(!checkReason())
            return 
        alert_modal.hide()
        fd = new FormData()
        fd.append('_token','{{csrf_token()}}')
        fd.append('order_id',id)
        fd.append('delete_reasons',$('#delete_reason').val())
        console.log('To delete latest Receive history')
        processing_modal.show()
        $.ajax({
            type: 'Post',
            url: 'delete/latest_receive_history',
            cache: false,
            contentType: false,
            processData: false,
            data : fd,
            success: function(data){
                //processing_modal.hide()
                console.log(data)
                window.location = '{{url('/repayment_list')}}'
            },
            error: function(err){
                console.log(err)
            }
        })
    }

    function confirmDeleteAllReceiveHistory(id){
        if(!checkReason())
            console.log(checkReason())
        alert_modal.hide()
        fd = new FormData()
        fd.append('_token','{{csrf_token()}}')
        fd.append('order_id',id)
        fd.append('delete_reasons',$('#delete_reason').val())
        console.log('To delete all Receive history')
        processing_modal.show()
        $.ajax({
            type: 'Post',
            url: 'delete/all_receive_history',
            cache: false,
            contentType: false,
            processData: false,
            data : fd,
            success: function(data){
                //processing_modal.hide()
                console.log(data)
                window.location = '{{url('/repayment_list')}}'
            },
            error: function(err){
                console.log(err)
            } 
        })
    }

    function checkReason(){
        console.log($('#delete_reason').val())
        if($('#delete_reason').val().length == 0){
            $("[id='final-confirm-button']").remove()
            $('#general_alert_content').text('You should not delete without reasons!')
            alert_modal.show();
            return 0
        }else
            return 1
    }

    @if(session('success'))
        $('#success_svg_content').text('Receive record has been deleted!')
        success_modal.show()
        @php 
            session()->forget('success');
        @endphp
    @endif
</script>
<script>
    $(document).ready(function() {
        now = "{{\Carbon\Carbon::now()->isoFormat('YYYYMMMDD-HHmm')}}"
        title = "SCF-order_list-"+now
        const maxFracNF = new Intl.NumberFormat("en", {
            minimumFractionDigits: 2,
        });
        var table = $('#table_id').DataTable({
                responsive: true,
                order: [[ 4, "desc" ]],
                "columnDefs": [
                    { targets: [1,2,3,4,5,6,7,8,9], className: 'dt-body-right'},
                    { "orderable": true, "targets": [3,4,5,6,7] },
                    { "orderable": false, "targets": '_all' },
                    { targets: '_all', className: 'dt-head-center'},
                    { targets: [11,12,13], visible: true},
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
                drawCallback: function () {
                    var api = this.api();
                    var sum_amount = maxFracNF.format(api.column( 6, {page:'current'} ).data().sum())
                    var sum_receive_amount = maxFracNF.format(api.column( 7, {page:'current'} ).data().sum())
                    var sum_delay_penalty = maxFracNF.format(api.column( 9, {page:'current'} ).data().sum())
                    html = `<tr class='font-bold'><td colspan='6'></td>
                            <td style='text-align:right'>${sum_amount}</td>
                            <td style='text-align:right'>${sum_receive_amount}</td>
                            <td></td>
                            <td style='text-align:right'>${sum_delay_penalty}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            </tr>`
                    $( api.table().footer() ).html(html);
                }
            })
        $('#table_id tbody').on('click', 'tr', function () {
            data = table.row(this).data()
            //console.log(table.row(this).data());
            //console.log(parseFloat(data[6]))
            $('#order_number').html(data[0])
            $('#buyer').html(data[1])
            $('#seller').html(data[2])
            $('#input_date').html(data[4])
            $('#due_date').html(data[5])
            $('#purchase_amount').html(data[6])
            $('#receive_amount').html(data[7])
            $('#receive_date').html(data[8])
            $('#interest').html(data[9])
            $('#delay_penalty').html(data[10])
            $('#paid_interest').html(data[11])
            $('#paid_delay_penalty').html(data[12])
            $('#payback_amount_to_supplier').html(data[14])
            $('#exceeded_amount').html(data[15])
            $('#tax').html(data[16])
            $('#paid_tax').html(data[17])
            $('#receive_comment').html(data[18])
            $('#outstanding_balance').html(data[19])
            $('#ten_percent_amount').html(data[20])
            $('#order_id').html(data[21])
            modal.show()
        });
    });
</script>

