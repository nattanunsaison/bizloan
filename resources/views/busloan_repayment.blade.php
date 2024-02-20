<x-busloanapp-layout>
    @section('title', __('Repayment'))
    @php
        $interest_and_delay_penalty = $order->installments->first()->calAccruInterestAndDelayPenalty(\Carbon\Carbon::now()->isoFormat('YYYY-MM-DD'));
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm text-sm sm:rounded-lg p-8 @if($interest_and_delay_penalty['is_delay']) bg-red-100 @else bg-white @endif">
                <h2 class="text-2xl my-5 font-extrabold dark:text-white">Business loan repayment order : {{ $order->order_number }}</h2>
                <input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}" />
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="relative mb-3" id="datepicker-disabled-dates" data-te-datepicker-init data-te-input-wrapper-init>
                            <input
                            type="text"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            placeholder="Select repayment date"
                            data-te-datepicker-toggle-ref
                            data-te-datepicker-toggle-button-ref
                            name="repayment_date"
                            id="repayment_date"
                            value=""
                            />
                            <label
                            for="repayment_date"
                            class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary"
                            >
                                Select repayment date
                            </label>
                        </div>
                        <div class="flex justify-left">
                            <div class="relative mb-3 w-full" data-te-input-wrapper-init>
                                <input
                                    id='receive_amount'
                                    type="text"
                                    class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                                    name="receive_amount"
                                    id="receive_amount"
                                    placeholder="Receive amount" />
                                <label
                                    for="receive_amount"
                                    class="pointer-events-none absolute top-0 left-3 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[1.15rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[1.15rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-neutral-200"
                                    >Receive amount
                                </label>
                            </div>
                        </div>                  
                    </div>

                    <div class=''>
                        <div class="flex justify-left">
                            <div class="relative mb-3 w-full" data-te-input-wrapper-init>
                                <textarea
                                    type="text"
                                    class="peer block min-h-[auto] w-full rounded border-0 bg-transparent py-[0.32rem] px-3 leading-[2.15] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                                    id="receive_comment"
                                    name="receive_comment"
                                    placeholder="Receive comment"></textarea>
                                <label
                                    for="receive_comment"
                                    class=" pointer-events-none absolute top-0 left-3 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[1.15rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[1.15rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-neutral-200"
                                    >Receive comment
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-6 gap-1 my-4">
                    <div class='font-bold text-base'>Drawdown ID :</div>
                    <div class='col-span-2' name='order_number' value='{{ $order->order_number }}'>{{ $order->order_number }}</div>
                    <div class='font-bold text-base'>Customer Tax ID :</div>
                    <div class='col-span-2' name='tax_id' value='{{ $order->tax_id }}'>{{ $order->customer->tax_id }}</div>
                </div>

                <div class="grid grid-cols-6 gap-1 my-3">
                    <div class='font-bold'>Customer name (en) :</div>
                    <div class='col-span-2' name='en_company_name' value='{{ $order->en_company_name }}'>{{ $order->customer->en_company_name }}</div>
                    <div class='font-bold'>Customer name (th) :</div>
                    <div class='col-span-2' name='th_company_name' value='{{ $order->th_company_name }}'>{{ $order->customer->th_company_name }}</div>
                    <div class='font-bold'>Offering grade :</div>
                    <div class='col-span-2' name='offering_grade' value='{{ $order->offering_grade }}'>{{ $order->product_offering->offering_grade }}</div>
                </div>
                
                <div class="grid grid-cols-6 gap-1 my-3">
                    <div class='font-bold'>Product code :</div>
                    <div class='col-span-2' name='product_code' value='{{ $order->product_code }}'>{{ $order->product_offering->product->product_code }}</div>
                    <div class='font-bold'>Product name :</div>
                    <div class='col-span-2' name='product_name' value='{{ $order->product_name }}'>{{ $order->product_offering->product->product_name }}</div>
                    <div class='font-bold'>Terms (days) :</div>
                    <div class='col-span-2' name='terms' value='{{ $order->terms }}'>{{ $order->product_offering->product->terms }}</div>
                    <div class='font-bold'>Amount :</div>
                    <div class='col-span-2' name='loan_amount' value='{{ $order->loan_amount }}'>{{ number_format($order->product_offering->product->loan_amount,2) }}</div>
                </div>

                <div class="grid grid-cols-6 gap-1 my-3">
                    <div class='font-bold'>Transfer date :</div>
                    <div class='col-span-2' name='purchase_ymd' value='{{ $order->purchase_ymd }}'>{{ $order->purchase_ymd }}</div>
                    <div class='font-bold'>Due date :</div>
                    <div class='col-span-2' name='due_ymd' value='{{ $order->due_ymd }}'>{{ $order->installments()->first()->due_ymd }}</div>
                    <div class='font-bold'>Last repayment :</div>
                    <div class='col-span-2' name='from_ymd' value='{{ $order->last_repayment_date }}'>{{ $order->last_repayment_date }}</div>
                    <div class='font-bold'>Day pass due (days) :</div>
                    <div class='col-span-2' name='daypassdue' value='{{ $order->daypassdue }}'>{{ $order->daypassdue }}</div>
                </div>

                <div class="grid grid-cols-6 gap-1 my-3">
                    <div class='font-bold'>Interest rate (%) :</div>
                    <div class='col-span-1' name='interest_rate' value='{{ $order->interest_rate }}'>{{ $order->product_offering->interest_rate }}</div>
                    <div class='font-bold'>Delay penalty rate (%) :</div>
                    <div class='col-span-1' name='delay_penalty_rate' value='{{ $order->delay_penalty_rate }}'>{{ $order->product_offering->delay_penalty_rate }}</div>
                    <div class='font-bold'>Discount rate (%) :</div>
                    <div class='col-span-1' name='discount_rate' value='{{ $order->discount_rate }}'>{{ $order->product_offering->discount_rate }}</div>
                    <div class='font-bold'>Effective interest rate (%) :</div>
                    <div class='col-span-1' name='effective_interest_rate' value='{{ $order->discount_rate }}'>{{ $interest_and_delay_penalty['effective_interest_rate'] }}</div>
                </div>

                <div class="grid grid-cols-10 gap-1 my-3">
                    <div class='col-span-2'></div>
                    <div class='font-bold text-center'>Principal</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-center'>Interest</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-center'>Delay penalty</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-center'>Total</div>
                    <div class='col-span-1'></div>
                    
                    <div class='font-bold'>Billing</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-right' name='all_principal_billing' value='{{ $interest_and_delay_penalty["billing_principal"] }}'>{{ number_format($interest_and_delay_penalty["billing_principal"],2) }}</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-right' name='all_interest_billing' value='{{ $interest_and_delay_penalty["billing_interest"]}}'>{{ number_format($interest_and_delay_penalty["billing_interest"],2) }}</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-right' name='all_delaypenalty_billing' value='{{ $interest_and_delay_penalty["billing_delay_penalty"] }}'>{{ number_format($interest_and_delay_penalty["billing_delay_penalty"],2) }}</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-right' name='all_total_billing' value='{{ $interest_and_delay_penalty["sum_billing"] }}'>{{ number_format($interest_and_delay_penalty['sum_billing'],2) }}</div>
                    <div class='col-span-1'></div>
                    
                    <div class='font-bold' href="#collapseExample">Paid</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-right' name='all_principal_paid' value='{{ $order->all_principal["paid"] }}'>{{ number_format($interest_and_delay_penalty['accru_paid_principal'],2) }}</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-right' name='all_interest_paid' value='{{ $order->all_interest["paid"] }}'>{{ number_format($interest_and_delay_penalty['accru_paid_interest'],2) }}</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-right' name='all_delaypenalty_paid' value='{{ $order->all_delaypenalty["paid"] }}'>{{ number_format($interest_and_delay_penalty['accru_paid_delay_penalty'],2) }}</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-right' name='all_total_paid' value='{{ $order->all_total["paid"] }}'>{{ number_format($interest_and_delay_penalty['accru_total_paid'],2) }}</div>
                    <div class='col-span-1'></div>
                    @php 
                        $total_paid_principal = 0;
                        $total_paid_interest = 0;
                        $total_paid_delay_penalty = 0;
                        $total_paid_amount = 0;
                    @endphp
                    @if( $order->receive_histories->count() > 0 )
                        <!-- <div class="!visible hidden" id="collapseExample" data-te-collapse-item> -->
                        <!-- <div class='col-span-1'></div> -->
                        <div class="!visible col-span-2 pl-5" id="collapseExample" data-te-collapse-item>
                            <!-- <div -->
                                <!-- class="block rounded-lg bg-white p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 dark:text-neutral-50"> -->
                                Partial Paid Date
                        </div>
                        <div class='col-span-8'></div>
                        @foreach ($order->receive_histories as $key => $value)
                            @php 
                                $detail = $value->receive_amount_detail;
                                if(!is_null($detail)){
                                    $total_paid_principal += $detail["paid_principal"];
                                    $total_paid_interest += $detail["paid_interest"];
                                    $total_paid_delay_penalty += $detail["paid_late_charge"];
                                }
                               
                            @endphp
                            <!-- <div class='col-span-1'></div> -->
                            <div class='col-span-2 pl-10' href="#collapseExample" >{{ \Carbon\Carbon::parse($value["receive_ymd"])->isoFormat('DD MMM YYYY') }}</div>
                            <div class='text-right'>{{ number_format(!is_null($detail) ? $detail["paid_principal"] : 0 ,2) }}</div>
                            <div class='col-span-1'></div>
                            <div class='text-right'>{{ number_format(!is_null($detail) ? $detail["paid_interest"] : 0,2) }}</div>
                            <div class='col-span-1'></div>
                            <div class='text-right'>{{ number_format(!is_null($detail) ? $detail["paid_late_charge"] : 0,2) }}</div>
                            <div class='col-span-1'></div>
                            <div class='text-right'>{{ number_format(!is_null($detail) ? $value["receive_amount"] : 0,2) }}</div>
                            <div class='col-span-1'></div>
                        @endforeach
                    @endif
                    @php 
                        $outstanding_principal = $interest_and_delay_penalty["billing_principal"] - $total_paid_principal;
                        $outstanding_interest = $interest_and_delay_penalty["billing_interest"] - $total_paid_interest;
                        $outstanding_delay_penalty = $interest_and_delay_penalty["billing_delay_penalty"] - $total_paid_delay_penalty;
                        $total_paid_amount = $total_paid_principal + $total_paid_interest + $total_paid_delay_penalty;
                        $total_outstanding = $outstanding_principal + $outstanding_interest +  $outstanding_delay_penalty;
                    @endphp
                    {{--
                    <div class='font-bold'>Outstanding</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-right' name='all_principal_outstanding' value='{{ $order->all_principal["outstanding"] }}'>{{ number_format($outstanding_principal,2) }}</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-right' name='all_interest_outstanding' value='{{ $order->all_interest["outstanding"] }}'>{{ number_format($outstanding_interest,2) }}</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-right' name='all_delaypenalty_outstanding' value='{{ $order->all_delaypenalty["outstanding"] }}'>{{ number_format($outstanding_delay_penalty,2) }}</div>
                    <div class='col-span-1'></div>
                    <div class='font-bold text-right' name='all_total_outstanding' value='{{ $order->all_total["outstanding"] }}'>{{ number_format($total_outstanding,2) }}</div>
                    <div class='col-span-1'></div>
                    --}}
                    
                    <div class='border-t font-bold'>Receive Amount</div>
                    <div class='border-t col-span-1'></div>
                    <div class='border-t font-bold text-right' name='principal_receive' value='0.00'>0.00</div>
                    <div class='border-t col-span-1'></div>
                    <div class='border-t font-bold text-right' name='interest_receive' value='0.00'>0.00</div>
                    <div class='border-t col-span-1'></div>
                    <div class='border-t font-bold text-right' name='delaypenalty_receive' value='0.00'>0.00</div>
                    <div class='border-t col-span-1'></div>
                    <div class='border-t font-bold text-right' name='total_receive' value='0.00'>0.00</div>
                    <div class='border-t col-span-1'></div>
                    
                    <div class='border-t font-bold'>Balance</div>
                    <div class='border-t col-span-1'></div>
                    <div class='border-t font-bold text-right' name='principal_balance' value='0.00'>{{ number_format($interest_and_delay_penalty["billing_principal"],2) }}</div>
                    <div class='border-t col-span-1'></div>
                    <div class='border-t font-bold text-right' name='interest_balance' value='0.00'>{{ number_format($interest_and_delay_penalty["billing_interest"],2) }}</div>
                    <div class='border-t col-span-1'></div>
                    <div class='border-t font-bold text-right' name='delaypenalty_balance' value='0.00'>{{ number_format($interest_and_delay_penalty["billing_delay_penalty"],2) }}</div>
                    <div class='border-t col-span-1'></div>
                    <div class='border-t font-bold text-right' name='total_balance' value='0.00'>{{ number_format($interest_and_delay_penalty['sum_billing'],2) }}</div>
                    <div class='border-t col-span-1'></div>
                </div>

                <x-button class="mt-5" onclick='open_confirmation_modal()'>
                    Confirm
                </x-button>

                <x-button class="mt-5 bg-red-500 hover:bg-red-700" onclick='resetRepaymentInfo()'>
                    Reset repayment history
                </x-button>
            </div>
        </div>
    </div>
</x-busloanapp-layout>
<x-alert>Receive amount cannot be 0! <br> Please enter Receive amount.</x-alert>
<x-processing></x-processing>

<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.2"></script>
<script>
    new AutoNumeric('#receive_amount',AutoNumeric.getPredefinedOptions().dotDecimalCharCommaSeparator);
</script>

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
                    <div id='result' class='text-white font-bold bg-green-500 text-center p-2 rounded' hidden></div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button onclick="confirmRepayment()" type="button" class="text-white bg-green-700 hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 font-medium px-5 py-2.5 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Confirm repayment</button>
                    <button onclick="closeModal()" type="button" class="text-white bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 font-medium px-5 py-2.5 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!--TE modal-->
<div
    data-te-modal-init
    class="fixed left-0 top-12 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
    id="main_modal"
    tabindex="-1"
    aria-labelledby="main_modal_label"
    aria-hidden="true">
    <div
        data-te-modal-dialog-ref
        class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out mx-auto max-w-4xl">
        <div
            class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
            <div
                class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
                <!--Modal title-->
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Receive amount detail confirmation
                </h3>
                <!--Close button-->
                <button
                    type="button"
                    class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                    data-te-modal-dismiss
                    onclick='closeMainModal()'
                    aria-label="Close">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6">
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6 w-full text-sm">
                <div id='result' class='text-white font-bold bg-green-500 text-center p-2 rounded' hidden></div>
                <div class="grid grid-cols-4 gap-1">
                    <div class='font-bold text-base'>Drawdown ID :</div>
                    <div class='col-span-1' name='confirm_order_number' value='{{ $order->order_number }}'>{{ $order->order_number }}</div>
                    <div class='font-bold text-base'>Customer Tax ID :</div>
                    <div class='col-span-1' name='confirm_tax_id' value='{{ $order->customer->tax_id }}'>{{ $order->customer->tax_id }}</div>
                    <div class='font-bold text-base'>Total receive amount :</div>
                    <div class='col-span-1' name='confirm_total_receive' value='0.00'>0.00</div>
                    <div class='font-bold text-base'>Total balance :</div>
                    <div class='col-span-1' name='confirm_total_balance' value='0.00'>0.00</div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button onclick="repayment_submit()" data-te-modal-dismiss data-te-ripple-init type="button" class="text-white bg-green-700 hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 font-medium px-5 py-2.5 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Confirm repayment</button>
                <button onclick="closeMainModal()" data-te-modal-dismiss type="button" class="text-white bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 font-medium px-5 py-2.5 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
            </div>
        </div>
    </div>

</div>

<script>
    //flowbite modal
    const modal_options = {
        backdrop: 'dynamic',
        placement: 'top-center',
    }
    const targetEl = document.getElementById('defaultModal');
    const modal = new Modal(targetEl,modal_options);

    /* const alertEl = document.getElementById('general_alert');
    const alert_modal = new Modal(alertEl); */

    const main_modal = new te.Modal(document.getElementById("main_modal"));

    const datepickerWithFilter = document.getElementById('datepicker-disabled-dates');

    const filterFunction = (date) => {
        // console.log(date)
        const repaymentDate = $('[name=from_ymd]').html();
        if(repaymentDate!='undefined' || repaymentDate!=''){
            const d = new Date(repaymentDate);
            // console.log(d)
            const isBeforeLastRepay = date < d
            // console.log(isBeforeLastRepay)
            return !isBeforeLastRepay;
        }
    }

    new te.Datepicker(datepickerWithFilter, { filter: filterFunction });

    $(document).ready(function() {
        $('input[name="repayment_date"]').on('input', function(ele) {
            var repayment_date = $('[name=repayment_date]').val()
            // console.log(ele)
            console.log(repayment_date)
            outstanding_calculation(ele)
        });
        
        $('input[name="receive_amount"]').on('input', function(ele) {
            var receive_amount = $('[name=receive_amount]').val()
            // console.log(ele)
            console.log(receive_amount)
            outstanding_calculation(ele)
        });
        
    });
    
    function outstanding_calculation(data){
        order_id = $('[name=order_id]').val()
        repayment_date = $('[name=repayment_date]').val()
        receive_amount = $('[name=receive_amount]').val()

        uri = '/api/outstanding_calculation'
        const fd = new FormData();
        fd.append('_token','{{csrf_token()}}');
        fd.append('order_id',order_id)
        fd.append('repayment_date',repayment_date)
        fd.append('receive_amount',receive_amount)

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
                // daypassdue
                $('[name=daypassdue]').val(data.date_diff_from_due);
                $('[name=daypassdue]').html(data.date_diff_from_due.toLocaleString());
                
                $('[name=all_principal_billing]').val(data.billing_principal);
                $('[name=all_principal_billing]').html(parseFloat(data.billing_principal).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                $('[name=all_interest_billing]').val(data.billing_interest);
                $('[name=all_interest_billing]').html(parseFloat(data.billing_interest).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                $('[name=all_delaypenalty_billing]').val(data.billing_delay_penalty);
                $('[name=all_delaypenalty_billing]').html(parseFloat(data.billing_delay_penalty).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                $('[name=all_total_billing]').val(data.sum_billing);
                $('[name=all_total_billing]').html(parseFloat(data.sum_billing).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));

                $('[name=effective_interest_rate]').val(data.effective_interest_rate);
                $('[name=effective_interest_rate]').html(parseFloat(data.effective_interest_rate).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                /*
                $('[name=all_principal_paid]').val(0);
                $('[name=all_principal_paid]').html(parseFloat(0).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                $('[name=all_interest_paid]').val(0);
                $('[name=all_interest_paid]').html(parseFloat(0).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                $('[name=all_delaypenalty_paid]').val(0);
                $('[name=all_delaypenalty_paid]').html(parseFloat(0).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                $('[name=all_total_paid]').val(0);
                $('[name=all_total_paid]').html(parseFloat(0).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));*/

                outstanding_principal = parseFloat(data.outstanding) - parseFloat('{{$total_paid_principal}}')
                $('[name=all_principal_outstanding]').val(outstanding_principal);
                $('[name=all_principal_outstanding]').html(parseFloat(outstanding_principal).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
     
                outstanding_interest = parseFloat(data.daily_interest) - parseFloat('{{$total_paid_interest}}')
                $('[name=all_interest_outstanding]').val(outstanding_interest);
                $('[name=all_interest_outstanding]').html(parseFloat(outstanding_interest).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                outstanding_delay_penalty = parseFloat(data.delay_penalty) - parseFloat('{{$total_paid_delay_penalty}}')
                $('[name=all_delaypenalty_outstanding]').val(outstanding_delay_penalty);
                $('[name=all_delaypenalty_outstanding]').html(parseFloat(outstanding_delay_penalty).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                total_outstanding = parseFloat(data.total_outstanding) - parseFloat('{{$total_paid_amount}}')
                $('[name=all_total_outstanding]').val(total_outstanding);
                $('[name=all_total_outstanding]').html(parseFloat(total_outstanding).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
            
                $('[name=principal_receive]').val(data.allocate_principal);
                $('[name=principal_receive]').html(parseFloat(data.allocate_principal).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                $('[name=interest_receive]').val(data.allocate_interest);
                $('[name=interest_receive]').html(parseFloat(data.allocate_interest).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                $('[name=delaypenalty_receive]').val(data.allocate_delay_penalty);
                $('[name=delaypenalty_receive]').html(parseFloat(data.allocate_delay_penalty).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                $('[name=total_receive]').val(data.receive_amount);
                $('[name=total_receive]').html(parseFloat(data.receive_amount).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                $('[name=confirm_total_receive]').val(data.receive_amount);
                $('[name=confirm_total_receive]').html(parseFloat(data.receive_amount).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
            
                $('[name=principal_balance]').val(data.balance_principal);
                $('[name=principal_balance]').html(parseFloat(data.balance_principal).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                $('[name=interest_balance]').val(data.balance_interest);
                $('[name=interest_balance]').html(parseFloat(data.balance_interest).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                $('[name=delaypenalty_balance]').val(data.balance_delay_penalty);
                $('[name=delaypenalty_balance]').html(parseFloat(data.balance_delay_penalty).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                
                $('[name=total_balance]').val(data.balance_total);
                $('[name=total_balance]').html(parseFloat(data.balance_total).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
                $('[name=confirm_total_balance]').val(data.balance_total);
                $('[name=confirm_total_balance]').html(parseFloat(data.balance_total).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}));
            },
            error: function(err){
                console.log(err);
            }
        })
    }

    function open_confirmation_modal(){
        console.log('confirm')
        var receive_amount = $('[name=receive_amount]').val()
        var receive_amount_float = parseFloat(receive_amount.replace(',',''))
        if(receive_amount_float > 0){
            main_modal.show()
        }else{
            alert_modal.show()
        }
    }

    function repayment_submit(){
        order_id = $('[name=order_id]').val()
        repayment_date = $('[name=repayment_date]').val()
        receive_amount = $('[name=receive_amount]').val()
        receive_comment = $('[name=receive_comment]').val()
        

        uri = '/repayment_submit'
        // uri = '/api/repayment_submit'
        const fd = new FormData();
        fd.append('_token','{{csrf_token()}}');
        fd.append('order_id',order_id)
        fd.append('repayment_date',repayment_date)
        fd.append('receive_amount',receive_amount)
        fd.append('receive_comment',receive_comment)

        $.ajax({
            type: 'POST',
            url: uri,
            cache: false,
            contentType: false,
            processData: false,
            data : fd,
            success: function(data){
                console.log('success')
                console.log(data)
                window.location.reload()
                //window.location.href = "/business_loan/summary"
            },error: function(err){
                console.log(err)
            }
        })
    }

    function resetRepaymentInfo(){
        order_id = $('[name=order_id]').val()     
        uri = '../reset_repayment_info'
        // uri = '/api/repayment_submit'
        const fd = new FormData();
        fd.append('_token','{{csrf_token()}}');
        fd.append('order_id',order_id)

        $.ajax({
            type: 'POST',
            url: uri,
            cache: false,
            contentType: false,
            processData: false,
            data : fd,
            success: function(data){
                console.log('success')
                console.log(data)
                window.location.reload()
            },error: function(err){
                console.log(err)
            }
        })
    }
</script>
