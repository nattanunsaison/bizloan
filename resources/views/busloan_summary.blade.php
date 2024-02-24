
<x-busloanapp-layout>
    @section('title', 'Business loan - Summary')
    
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-scroll text-sm shadow-sm sm:rounded-lg p-2">
                <table id="table_id" class="display table-auto">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order number</th>
                            <th>Product ID</th>
                            <th>Interest rate%</th>
                            <th>Discount rate%</th>
                            <th>Delay penalty%</th>
                            <th>Tax ID</th>
                            <th>Company Name (en)</th>
                            <th>Company Name (th)</th>
                            <th>Amount</th>
                            <th>Interest (old)</th>
                            <th>Draw down date</th>
                            <th>Bill date</th>
                            <th>Due date</th>
                            <th>Date diff from draw down</th>
                            <th>Effective interest rate%</th>
                            <th>Interest (new)</th>
                            <th>Date diff from due</th>
                            <th>Delay penalty</th>
                            <th>Paid up date</th>
                            <th>Cancelled at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $key => $order)
                        @php 
                            $interest_with_date = $order->installments->first()->calAccruInterestAndDelayPenalty(\Carbon\Carbon::now()->isoFormat('YYYY-MM-DD'));
                            $is_delay_and_not_paid_up = is_null($order->paid_up_ymd) && ($interest_with_date['is_delay'] == 'Yes');
                            $is_paid_up = !is_null($order->paid_up_ymd);
                        @endphp
                        <tr>
                            <!-- <td>{{$key+1}}</td> -->
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{$order->id}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{$order->order_number}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{$order->product_offering->product->product_code}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{number_format($order->product_offering->interest_rate,2)}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{number_format($order->product_offering->discount_rate,2)}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{number_format($order->product_offering->delay_penalty_rate,2)}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{$order->customer->tax_id}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif class='text-left'>{{$order->customer->en_company_name}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif class='text-left'>{{$order->customer->th_company_name}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif class='text-right'>{{number_format($order->installments->first()->principal,2)}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif class='text-right'>{{number_format($order->installments->first()->interest,2)}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{$order->purchase_ymd}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{$order->bill_date}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{$order->installments->first()->due_ymd}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{number_format($interest_with_date['date_diff_form_last_receive'])}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{number_format($interest_with_date['effective_interest_rate'],2)}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{number_format($interest_with_date['daily_interest'],2)}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{number_format($interest_with_date['date_diff_from_due'])}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{number_format($interest_with_date['delay_penalty'],2)}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{$order->installments->first()->paid_up_ymd}}</td>
                            <td @if($is_delay_and_not_paid_up) class='bg-red-500 text-white' @elseif($is_paid_up) class='bg-gray-400 text-white' @endif>{{$order->canceled_at}}</td>
                            
                            <td class="text-center">
                                <div class='flex flex-row items-center gap-4'>
                                    <a href="repayment?order_id={{ $order->id }}">
                                        <button
                                        type="submit"
                                        class="inline-block w-50 bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase text-white hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]"
                                        data-te-ripple-init
                                        data-te-ripple-color="light">
                                            Repay
                                        </button>
                                    </a>
                                    <button
                                        type="button"
                                        class="inline-block w-50 bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase text-white hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]"
                                        data-te-ripple-init
                                        data-te-ripple-color="light"
                                        onclick='sendStatement({{$order->id}})'
                                        >
                                            Send statement
                                    </button>
                                </div>
                            </td>
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

<!-- Main modal -->
<div id="defaultModal" tabindex="-1" aria-hidden="true" class="fixed bg-gray-200 top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Information
                </h3>
                <button onclick="closeModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6 w-full text-normal flex justify-center" id='modal-body'>
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <!-- Heroicon name: outline/exclamation -->
                    <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                        <path fill="red" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                            <animateTransform 
                                attributeName="transform" 
                                attributeType="XML" 
                                type="rotate"
                                dur="1s" 
                                from="0 50 50"
                                to="360 50 50" 
                                repeatCount="indefinite" />
                        </path>
                    </svg>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                
                <button onclick="closeModal()" type="button" class="text-white bg-gray-400 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5  focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        now = "{{\Carbon\Carbon::now()->isoFormat('YYYYMMMDD-HHmm')}}"
        title = "SCF-order_list-"+now
        var table = $('#table_id').DataTable({
                // responsive: true,
                order: [[ 0, "asc" ]],
                "columnDefs": [
                    { targets: [1,2,3], className: 'dt-body-left'},
                    { targets: [3,4,5,14,15,16,17,18], className: 'dt-body-right'},
                    { "orderable": true, "targets": [0,1,7,8,9,10,11] },
                    { "orderable": false, "targets": '_all' },
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

<script>
    const targetEl = document.getElementById('defaultModal');
    const modal = new Modal(targetEl);
    //modal.show()
    function closeModal(){
        modal.toggle();
    }

    function sendStatement(id){    
        uri = '../send_statement'
        // uri = '/api/repayment_submit'
        const fd = new FormData();
        fd.append('_token','{{csrf_token()}}');
        fd.append('order_id',id)
        modal.show()
        $.ajax({
            type: 'POST',
            url: uri,
            cache: false,
            contentType: false,
            processData: false,
            data : fd,
            success: function(data){
                console.log(data)
                modal.show()
                $('#modal-body').html('Sent statement successfully!')
                //window.location.reload()
            },error: function(err){
                console.log(err)
            }
        })
    }
</script>