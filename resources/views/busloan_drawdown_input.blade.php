
<x-busloanapp-layout>
    {{-- @section('title', __('Dashboard')) --}}
    @section('title', 'Business loan - Customers registration')
    
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden text-sm shadow-sm sm:rounded-lg p-2">
                {{-- <div class="block max-w-md rounded-lg bg-white p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700"> --}}
                <div class="my-6 text-lg">Drawdown Input</div>
                <div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative mb-6" data-te-input-wrapper-init>
                            <input data-te-input-state-active
                            type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-neutral-100 px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:bg-neutral-700 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            id="con_id"
                            aria-describedby="con_id"
                            placeholder="Con ID"
                            value="{{$customer_data->id}}"
                            disabled />
                            <label
                            for="con_id"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                            >Con ID
                            </label>
                        </div>

                        <div class="relative mb-6" data-te-input-wrapper-init>
                            <input data-te-input-state-active
                            type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-neutral-100 px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:bg-neutral-700 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            name="tax_id"
                            id="tax_id"
                            aria-describedby="tax_id"
                            placeholder="Tax ID"
                            value="{{$customer_data->tax_id}}"
                            disabled />
                            <label
                            for="tax_id"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                            >Tax ID
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative mb-6" data-te-input-wrapper-init>
                            <input data-te-input-state-active
                            type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-neutral-100 px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:bg-neutral-700 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            id="companyNameEN"
                            aria-describedby="companyNameEN"
                            placeholder="Company Name (en)"
                            value="{{$customer_data->en_company_name}}"
                            disabled />
                            <label
                            for="companyNameEN"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                            >Company Name (en)
                            </label>
                        </div>

                        <div class="relative mb-6" data-te-input-wrapper-init>
                            <input data-te-input-state-active
                            type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-neutral-100 px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:bg-neutral-700 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            id="companyNameTH"
                            aria-describedby="companyNameTH"
                            placeholder="Company Name (th)"
                            value="{{$customer_data->th_company_name}}"
                            disabled />
                            <label
                            for="companyNameTH"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                            >Company Name (th)
                            </label>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-6 ">
                            <select data-te-select-init name="product">
                                
                                @foreach($products as $product)
                                    {{-- <option value="{{ $customer->id }}">{{ $customer->tax_id }}</option> --}}
                                    <option value="{{$product->id}}">{{$product->product_name}}</option>
                                @endforeach
                            </select>
                            <label data-te-select-label-ref>Product</label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative mb-6" data-te-input-wrapper-init>
                            <input data-te-input-state-active
                            type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-neutral-100 px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:bg-neutral-700 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            id="product_code"
                            aria-describedby="product_code"
                            placeholder="Product code"
                            value="{{$products[0]->product_code}}"
                            disabled />
                            <label
                            for="product_code"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                            >Product code
                            </label>
                        </div>

                        <div class="relative mb-6" data-te-input-wrapper-init>
                            <input data-te-input-state-active
                            type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-neutral-100 px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:bg-neutral-700 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            id="terms"
                            aria-describedby="terms"
                            placeholder="terms"
                            value="{{$products[0]->terms}}"
                            disabled />
                            <label
                            for="terms"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                            >Terms (days)
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative mb-6" data-te-input-wrapper-init>
                            <input data-te-input-state-active
                            type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-neutral-100 px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:bg-neutral-700 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            id="interest_rate"
                            aria-describedby="interest_rate"
                            placeholder="Interest rate"
                            value="{{$products[0]->interest_rate}}"
                            disabled />
                            <label
                            for="interest_rate"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                            >Interest rate
                            </label>
                        </div>

                        <div class="relative mb-6" data-te-input-wrapper-init>
                            <input data-te-input-state-active
                            type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-neutral-100 px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:bg-neutral-700 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            id="delay_penalty_rate"
                            aria-describedby="delay_penalty_rate"
                            placeholder="Delay penalty rate"
                            value="{{$products[0]->delay_penalty_rate}}"
                            disabled />
                            <label
                            for="delay_penalty_rate"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                            >Delay penalty rate
                            </label>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative mb-6" data-te-input-wrapper-init>
                            <input data-te-input-state-active
                            type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-neutral-100 px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:bg-neutral-700 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            id="discount_rate"
                            aria-describedby="discount_rate"
                            placeholder="Discount rate"
                            value="{{$products[0]->discount_rate}}"
                            disabled />
                            <label
                            for="discount_rate"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                            >Discount rate
                            </label>
                        </div>

                        <div class="relative mb-6" data-te-input-wrapper-init>
                            <input data-te-input-state-active
                            type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-neutral-100 px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:bg-neutral-700 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            name="loan_amount"
                            id="loan_amount"
                            aria-describedby="loan_amount"
                            placeholder="Loan amount"
                            {{-- value="{{ number_format($products[0]->loan_amount,2) }}" --}}
                            value="{{ number_format($products[0]->loan_amount,2) }}"
                            disabled />
                            <label
                            for="loan_amount"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                            >Loan amount
                            </label>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative mb-6" data-te-datepicker-init data-te-input-wrapper-init>
                            <input
                            type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            placeholder="Select transfer date"
                            data-te-datepicker-toggle-ref
                            data-te-datepicker-toggle-button-ref
                            name="transfer_date"
                            id="transfer_date"
                            value=""
                            />
                            <label
                            for="transfer_date"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                            >
                                Select transfer date
                            </label>
                        </div>

                        <div class="relative mb-6" data-te-input-wrapper-init>
                            <input data-te-input-state-active
                            type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-neutral-100 px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:bg-neutral-700 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            name="due_date"
                            id="due_date"
                            aria-describedby="due_date"
                            placeholder="Due date"
                            value=""
                            disabled />
                            <label
                            for="due_date"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                            >Due date
                            </label>
                        </div>
                    </div>

                    <button
                    type="submit"
                    class="inline-block w-50 bg-red-500 px-6 pb-2 pt-2.5 text-xs font-medium uppercase text-white hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]"
                    data-te-ripple-init
                    data-te-ripple-color="light">
                    Cancel
                    </button>
                    <!--Submit button-->
                    <button
                    type="submit"
                    class="inline-block w-50 bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase text-white hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]"
                    data-te-ripple-init
                    data-te-ripple-color="light"
                    name="drawdown_input_btn"
                    >
                    Submit
                    </button>
                </div>
                {{-- </div> --}}
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

        $('[name="product"]').on('change', function(ele) {
            product_offering_id = $('[name=product] option:selected').val()
            // console.log(ele)
            console.log(product_offering_id)
            product_information(ele)
        });

        $('input[name="transfer_date"]').on('input', function(ele) {
            var transfer_date = $('[name=transfer_date]').val()
            // console.log(ele)
            console.log(transfer_date)
            duedate_calculation(ele)
        });
        
        $('button[name=drawdown_input_btn]').on('click', function(ele){
            submit_drawdown_input(ele)
        });
    });

    const targetEl = document.getElementById('defaultModal');
    const modal = new Modal(targetEl);

    function closeModal(){
        modal.toggle();
    }

    function product_information(data){
        product_offering_id = $('[name=product] option:selected').val()
        uri = '/api/product_information'
        const fd = new FormData();
        fd.append('_token','{{csrf_token()}}');
        fd.append('offering_id',product_offering_id)
        
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
                
                $('#product_code').val(data.product_code)
                $('#terms').val(data.terms)
                $('#interest_rate').val(data.interest_rate)
                $('#delay_penalty_rate').val(data.delay_penalty_rate)
                $('#discount_rate').val(data.discount_rate)
                $('#loan_amount').val(parseFloat(data.loan_amount).toLocaleString("en", { minimumFractionDigits: 2 }))
            },
            error: function(err){
                console.log(err);
            }
        })
    }

    function duedate_calculation(data){
        product_offering_id = $('[name=product] option:selected').val()
        transfer_date = $('[name=transfer_date]').val()

        
        uri = '/api/duedate_calculation'
        const fd = new FormData();
        fd.append('_token','{{csrf_token()}}');
        fd.append('product_offering_id',product_offering_id)
        fd.append('transfer_date',transfer_date)

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
                $('input[name=due_date]').val(data);
                $('input[name=due_date]').attr('data-te-input-state-active','');
            },
            error: function(err){
                console.log(err);
            }
        })
    }

    function submit_drawdown_input(data){
        console.log(data);
        closest_div = $('.formdata').closest(data)
        tax_id = $('[name=tax_id]').val()
        loan_amount = $('[name=loan_amount]').val()
        product_offering_id = $('[name=product] option:selected').val()
        transfer_date = $('[name=transfer_date]').val()
        due_date = $('[name=due_date]').val()
        
        uri = '/api/drawdown_input'
        const fd = new FormData();
        fd.append('_token','{{csrf_token()}}');
        fd.append('tax_id',tax_id)
        fd.append('product_offering_id',product_offering_id)
        fd.append('loan_amount',loan_amount)
        fd.append('transfer_date',transfer_date)
        fd.append('due_date',due_date)
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
                
                window.alert(data.message);
                window.location.href = "/business_loan/summary";
            },
            error: function(err){
                console.log(err);
            }
        })
    }
</script>