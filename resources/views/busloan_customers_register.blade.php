
<x-busloanapp-layout>
    {{-- @section('title', __('Dashboard')) --}}
    @section('title', 'Business loan - Customers registration')
    
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden text-sm shadow-sm sm:rounded-lg p-5 formdata" data-te-validation-init>
                <div class="my-6 text-lg">Customer Registration</div>                    
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative mb-6 mr-4 min-h-[1.5rem] pl-[1.5rem]">
                        <input
                        class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:border-primary dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                        type="radio"
                        name="existing_customer"
                        id="new_cus"
                        value="0" checked />
                        <label
                        class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                        for="new_cus">
                            New customer
                        </label>
                    </div>
                    
                    <div class="relative mb-6 mr-4 min-h-[1.5rem] pl-[1.5rem]">
                        <input
                        class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:border-primary dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                        type="radio"
                        name="existing_customer"
                        id="existing_cus"
                        value="1" />
                        <label
                        class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                        for="existing_cus">
                            Existing customer
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 new_cus">
                    <div class="relative mb-6" data-te-input-wrapper-init>
                        <input
                        type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                        id="input_con_id"
                        placeholder="Contractor ID" required />
                        <label
                        for="input_con_id"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary">
                            Contractor ID
                        </label>
                    </div>
                    <div class="relative mb-6" data-te-input-wrapper-init>
                        <input
                        type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                        id="input_tax_id"
                        placeholder="Tax ID" />
                        <label
                        for="input_tax_id"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary">
                            Tax ID
                        </label>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 existing_cus hidden">
                    <div class="mb-6 ">
                        <select class="relative" data-te-select-init data-te-select-filter="true" name="select_con_id" onChange="select_conid_change(this)">
                            <option value="" hidden selected></option>
                            @if ($customers)
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->id }}</option>
                                @endforeach
                            @endif
                        </select>
                        <label data-te-select-label-ref>Contractor ID</label>
                        {{-- <input name="con_id" value="" hidden /> --}}
                    </div>
                    <div class="mb-6 ">
                        <select class="relative" data-te-select-init data-te-select-filter="true" name="select_tax_id" onChange="select_taxid_change(this)">
                            <option value="" hidden selected></option>
                            @if ($customers)
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->tax_id }}</option>
                                @endforeach
                            @endif
                        </select>
                        <label data-te-select-label-ref>Tax ID</label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="relative mb-6" data-te-input-wrapper-init
                    data-te-validate="input"
                    data-te-validation-ruleset="isRequired">
                        <input
                        type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                        id="en_company_name"
                        name="en_company_name"
                        aria-describedby="en_company_name"
                        placeholder="Company Name (en)" />
                        <label
                        for="en_company_name"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                        >Company Name (en)
                        </label>
                    </div>

                    <div class="relative mb-6" data-te-input-wrapper-init
                    data-te-validate="input"
                    data-te-validation-ruleset="isRequired">
                        <input
                        type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                        id="th_company_name"
                        name="th_company_name"
                        aria-describedby="th_company_name"
                        placeholder="Company Name (th)" />
                        <label
                        for="th_company_name"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                        >Company Name (th)
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="relative mb-6" data-te-input-wrapper-init
                    data-te-validate="input"
                    data-te-validation-ruleset="isRequired">
                        <input
                        type="email"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                        id="customer_email"
                        name="customer_email"
                        aria-describedby="customer_email"
                        placeholder="Customer E-mail" />
                        <label
                        for="customer_email"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                        >Customer E-mail
                        </label>
                    </div>

                    <div class="relative mb-6" data-te-input-wrapper-init
                    data-te-validate="input"
                    data-te-validation-ruleset="isRequired">
                        <input
                        type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                        id="customer_phone_number"
                        name="customer_phone_number"
                        aria-describedby="customer_phone_number"
                        placeholder="Customer Tel." />
                        <label
                        for="customer_phone_number"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                        >Customer Tel.
                        </label>
                    </div>
                </div>

                <div class="relative mb-6" data-te-input-wrapper-init
                data-te-validate="input"
                data-te-validation-ruleset="isRequired">
                    <input
                        type="text"
                        class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                        id="customer_address"
                        name="customer_address"
                        placeholder="Address" />
                    <label
                        for="customer_address"
                        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-neutral-200"
                        >Address
                    </label>
                </div>
        
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-6 ">
                        <select data-te-select-init name="customer_status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <label data-te-select-label-ref>Customer status</label>
                    </div>
                    <div class="mb-6 ">
                        <select data-te-select-init name="master_agreement">
                            <option value="0">Uncomplete</option>
                            <option value="1">Complete</option>
                        </select>
                        <label data-te-select-label-ref>Master agreement</label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-6 ">
                        <select data-te-select-init name="offering_grade">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                        <label data-te-select-label-ref>Offering grade</label>
                    </div>
                    <div class="mb-6 ">
                        <select data-te-select-init name="business_loan_amount">
                            <option value="100000.00">100,000.00</option>
                            <option value="300000.00">300,000.00</option>
                            <option value="500000.00">500,000.00</option>
                            <option value="1000000.00">1,000,000.00</option>
                        </select>
                        <label data-te-select-label-ref>Business loan amount</label>
                    </div>
                </div>

                <button
                type="submit"
                class="inline-block w-50 bg-red-500 px-6 pb-2 pt-2.5 text-xs font-medium uppercase text-white hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]"
                data-te-ripple-init
                data-te-ripple-color="light">
                    Cancel
                </button>
                <button
                type="submit"
                class="inline-block w-50 bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase text-white hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]"
                data-te-ripple-init
                data-te-ripple-color="light"
                id="register_btn"
                name="register_btn"
                {{-- data-te-submit-btn-ref --}}
                >
                    Register
                </button>
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
        title = "SCF-order_list-"+now
        var table = $('#table_id').DataTable({
            responsive: true,
            order: [[ 4, "desc" ]],
            "columnDefs": [
                { targets: [1,2,3], className: 'dt-body-right'},
                { "orderable": true, "targets": [3,4,5,6,7] },
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

        $('input[name="existing_customer"]').on('change', function(ele) {
            // console.log(ele);
            // console.log($(this).attr('id'));
            var this_id = $(this).attr('id');
            if(this_id == 'existing_cus'){
                console.log($('.existing_cus'));
                $('.new_cus').addClass('hidden');
                $('.existing_cus').removeClass('hidden');
            }else{
                console.log($('.new_cus'));
                $('.existing_cus').addClass('hidden');
                $('.new_cus').removeClass('hidden');
            }
        });
        $('button[name=register_btn]').on('click', function(ele){
            // console.log(ele);
            submit_register(ele);
        });
    });

    function select_conid_change(ele){
        var conid = $(ele).val();
        this.get_existing_customer_data(conid);
    }
    function select_taxid_change(ele){
        var conid = $(ele).val();
        this.get_existing_customer_data(conid);
    }

    function get_existing_customer_data(data){
        console.log(data);
        uri = '/api/existing_customer_information';
        const fd = new FormData();
        fd.append('_token','{{csrf_token()}}');
        fd.append('contractor_id',data);
        $.ajax({
            type: 'POST',
            url: uri,
            cache: false,
            contentType: false,
            processData: false,
            data : fd,
            success: function(data){
                console.log('success');
                console.log(data);
                $('select[name=select_con_id]').val(data.id);
                $('[name=select_con_id] option[value="'+data.id+'"]').attr('selected','selected');
                $('[name=select_tax_id] option[value="'+data.id+'"]').attr('selected','selected');
                $('input[name=en_company_name]').val(data.en_company_name);
                $('input[name=en_company_name]').attr('data-te-input-state-active','');
                $('input[name=th_company_name]').val(data.th_company_name);
                $('input[name=th_company_name]').attr('data-te-input-state-active','');
                $('input[name=customer_email]').val(data.owner_email);
                $('input[name=customer_email]').attr('data-te-input-state-active','');
                $('input[name=customer_phone_number]').val(data.owner_mobile_number);
                $('input[name=customer_phone_number]').attr('data-te-input-state-active','');
                $('input[name=customer_address]').val(data.address);
                $('input[name=customer_address]').attr('data-te-input-state-active','');
            },
            error: function(err){
                console.log(err);
            }
        });
    }

    function submit_register(data){
        console.log(data);
        closest_div = $('.formdata').closest(data)
        existing_customer = $('[name=existing_customer]:checked').val()

        if(existing_customer==0){
            con_id = null
            tax_id = $('[id=input_tax_id]').val()
        }else{
            con_id = $('[name=select_con_id] option:selected').val()
            tax_id = $('[name=select_tax_id] option:selected').text()
        }

        en_company_name = $('[name=en_company_name]').val()
        th_company_name = $('[name=th_company_name]').val()
        customer_email = $('[name=customer_email]').val()
        customer_phone_number = $('[name=customer_phone_number]').val()
        customer_address = $('[name=customer_address]').val()
        customer_status = $('[name=customer_status] option:selected').val()
        master_agreement = $('[name=master_agreement] option:selected').val()
        offering_grade = $('[name=offering_grade] option:selected').val()
        business_loan_amount = $('[name=business_loan_amount] option:selected').val()
        
        uri = '/api/register_customer'
        const fd = new FormData();
        fd.append('_token','{{csrf_token()}}');
        fd.append('existing_customer',existing_customer)
        fd.append('con_id',con_id)
        fd.append('tax_id',tax_id)
        fd.append('en_company_name',en_company_name)
        fd.append('th_company_name',th_company_name)
        fd.append('customer_email',customer_email)
        fd.append('customer_phone_number',customer_phone_number)
        fd.append('customer_address',customer_address)
        fd.append('customer_status',customer_status)
        fd.append('master_agreement',master_agreement)
        fd.append('offering_grade',offering_grade)
        fd.append('business_loan_amount',business_loan_amount)
        fd.append('staff_username', $('[data-username]').attr('data-username'))
        fd.append('staff_userid', $('[data-userid]').attr('data-userid'))

        $.ajax({
            type: 'POST',
            url: uri,
            cache: false,
            contentType: false,
            processData: false,
            data : fd,
            success: function(data){
                console.log('success');
                console.log(data);
                window.alert("ทำการเพิ่มลูกค้า Tax ID : "+data.tax_id+" เรียบร้อย");
                window.location.href = "/business_loan/customers";
            },
            error: function(err){
                console.log(err);
            }
        })
    }

    const targetEl = document.getElementById('defaultModal');
    const modal = new Modal(targetEl);

    function closeModal(){
        modal.toggle();
    }

</script>