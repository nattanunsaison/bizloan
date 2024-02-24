<x-busloanapp-layout>
    @section('title', 'Business loan - Customers')
    
    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dt-buttons float-right" style="">
                <a class="dt-button buttons-html5" tabindex="0" type="button" href="customers/register">
                <button
                type="submit"
                class="inline-block w-50 bg-primary px-6 pb-2 pt-2.5 text-sm font-medium uppercase text-white hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]"
                data-te-ripple-init
                data-te-ripple-color="light">
                    Add Customer
                </button>
                </a>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden text-sm shadow-sm sm:rounded-lg p-2">
                <table id="table_id" class="table display nowrap">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Con ID</th>
                            <th>Tax ID</th>
                            <th>Company Name (en)</th>
                            <th>Company Name (th)</th>
                            <th>Business loan amount</th>
                            <th>Offering grade</th>
                            <th>KYC PIC</th>
                            <th>Customer status</th>
                            <th>Master agreement</th>
                            <th>Ready status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $key => $value)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$value->con_id}}</td>
                                <td>{{$value->tax_id}}</td>
                                <td>{{$value->en_company_name}}</td>
                                <td>{{$value->th_company_name}}</td>
                                <td class='text-right'>{{number_format($value->business_loan_amount,2)}}</td>
                                <td class='text-right'>{{$value->offering_grade}}</td>
                                <td>{{$value->username}}</td>
                                <td>{{$value->status_text}}</td>
                                <td>{{$value->master_agreement_text}}</td>
                                <td>{{$value->ready_status_text}}</td>
                                <td class="text-center">
                                    <a href="drawdown/input?conid={{ $value->id }}">
                                        <button
                                        type="submit"
                                        class="inline-block w-50 bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase text-white hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]"
                                        data-te-ripple-init
                                        data-te-ripple-color="light">
                                            Input Drawdown
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script> --}}

<!-- Main modal -->
<div id="defaultModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
    <div class="relative w-full h-full max-w-4xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Conversation history
                </h3>
                <button onclick="closeModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6 w-full text-sm">
                <span id='company_name'>Line user id</span><br>
                <span id='contractor_id'>Contractor id</span>
                <div id='result' class='text-white font-bold bg-green-500 text-center p-2 rounded' hidden></div>
                <div class='grid grid-cols-3 gap-4'>
                    <div class='col-span-2'>
                        <input class='w-full rounded' type="text" placeholder="Type text to send to this contractor here" name='comment' id='comment'>
                    </div>
                    <div calss='col-span-1'>
                        <button id="submit_comment" type="button" class="{{config('colors.bg_confirm_color_set')}} focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Send</button>
                    </div>
                </div>
                <div id='modal_conversation_history'>
                    <table id='conver_table' class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Text</th>
                                <th>Created at</th>
                                <th>Contractor ID</th>
                            </tr>  
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Text</th>
                                <th>Created at</th>
                                <th>Contractor ID</th>
                            </tr>  
                        </tfoot>
                    </table>
                    
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                
                <button onclick="closeModal()" type="button" class="text-white bg-gray-400 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        now = "{{\Carbon\Carbon::now()->isoFormat('YYYYMMMDD-HHmm')}}"
        // title = "SCF-order_list-"+now
        // var table = $('#table_id').DataTable({
        //     scrollX: true,
        //     responsive: true,
        //     order: [[ 4, "desc" ]],
        //     "columnDefs": [
        //         // { targets: [1,2,3], className: 'dt-body-right'},
        //         // { "orderable": true, "targets": [3,4,5,6,7] },
        //         // { "orderable": false, "targets": '_all' },
        //     ],
        //     dom: '<"top"Bfl>rt<"bottom"ip><"clear">',
        //     buttons: [
        //         {
        //             extend: 'csvHtml5',
        //             title: title
        //         },
        //         {
        //             extend: 'excelHtml5',
        //             title: title
        //         },
        //     ],
        // });
    });


</script>

<script>
    const targetEl = document.getElementById('defaultModal');
    const modal = new Modal(targetEl);

    function closeModal(){
        modal.toggle();
    }

</script>