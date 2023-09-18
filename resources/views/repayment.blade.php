<x-app-layout>
    @section('title', __('Repayment'))

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm text-sm sm:rounded-lg p-2">
                <h2 class="text-4xl font-extrabold dark:text-white">{{$order->order_number}}</h2>
                <div class="flex items-left justify-left">
                    <div
                        id='myDatepicker'
                        class="relative mb-3 w-1/2 xl:w-96"
                        data-te-datepicker-init
                        data-te-input-wrapper-init>
                        <input
                            id='date'
                            type="text"
                            data-te-format='dd mmmm yyyyy'
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent py-[0.32rem] px-3 leading-[2.15] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            placeholder="Select a date" />

                        <label
                            for="floatingInput"
                            class="pointer-events-none absolute top-0 left-3 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[1.15rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[1.15rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-neutral-200"
                            >Select a date to repay</label>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-1">
                    <div class='font-bold'>Buyer</div>
                    <div class='col-span-1'>{{$order->contractor->en_company_name}}</div>
                    <div class='font-bold'>Seller</div>
                    <div class='col-span-1'>{{$order->dealer->en_dealer_name}}</div>
                    <div class='font-bold'>Purchase date</div>
                    <div class='col-span-1'>{{\Carbon\Carbon::parse($order->purchase_ymd)->isoFormat('DD MMMM YYYY')}}</div>
                    <div class='font-bold'>Input date</div>
                    <div class='col-span-1'>{{\Carbon\Carbon::parse($order->input_ymd)->isoFormat('DD MMMM YYYY')}}</div>
                    <div class='font-bold'>Due date</div>
                    <div class='col-span-1'>{{\Carbon\Carbon::parse($order->installments->first()->due_ymd)->isoFormat('DD MMMM YYYY')}}</div>
                    @php  
                        if(count($order->receive_records)==0){
                            $delay_penalty_and_interest = $order->installments->first()->delayPenalty();  
                        }
                        else{
                            $delay_penalty_and_interest = $order->receive_records->last()->receive_amount_detail->delayPenalty();
                        }
                        $date_diff_from_last_due = $delay_penalty_and_interest['date_diff_from_last_due'];
                        $date_diff_from_due = $delay_penalty_and_interest['date_diff_from_due'];
                    @endphp
                    <div class='font-bold'>Days from input date</div>
                    <div class='col-span-1' id='date_diff_from_last_due'>{{number_format($date_diff_from_last_due)}}</div>
                    <div class='font-bold'>Days from due</div>
                    <div class='col-span-1' id='date_diff_from_due'>{{number_format($date_diff_from_due)}}</div>
                    <div class='col-span-2'></div>
                    <div class='font-bold'>Principal 100%</div>
                    <div class='text-xl text-right'>{{number_format($order->purchase_amount,2)}}</div>
                    <div class='col-span-2'></div>
                    <div class='font-bold'>Principal 10%</div>
                    <div class='text-xl text-right'>{{number_format($order->purchase_amount*0.1,2)}}</div>
                    <div class='col-span-2'></div>
                    @php
                        $paid_principal = 0;
                        $paid_interest = 0;
                        $paid_delay_penalty = 0;
                        $outstanding_principal = $order->purchase_amount;
                        $outstanding_interest = 0;
                        $outstanding_delay_penalty = 0;
                        $outstanding_balance = 0;
                        $total_interest = 0;
                        $total_delay_penalty = 0;
                        $this_period_interest = 0;
                        $this_period_delay_penalty=0;
                        if(count($order->receive_records)==0){
                            //$delay_penalty_and_interest = $order->installments->first()->delayPenalty();
                            $outstanding_balance = $order->purchase_amount;   
                        }
                        else{
                            //$delay_penalty_and_interest = $order->receive_records->last()->receive_amount_detail->delayPenalty();
                            $outstanding_i =$order->receive_records->last()->receive_amount_detail->interest;
                            $outstanding_d = $order->receive_records->last()->receive_amount_detail->late_charge;
                            //echo "outstanding_i:$outstanding_i,outstanding_d:$outstanding_d<br>";
                            foreach($order->receive_records as $record){
                                $receive_amount_detail = $record->receive_amount_detail;
                                $paid_principal += $receive_amount_detail->paid_principal;
                                $paid_interest += $receive_amount_detail->paid_interest - $receive_amount_detail->waive_interest;
                                $paid_delay_penalty += $receive_amount_detail->paid_late_charge - $receive_amount_detail->waive_late_charge;
                                //$outstanding_interest += $receive_amount_detail->interest - $receive_amount_detail->paid_interest - $receive_amount_detail->waive_interest;
                                //$outstanding_delay_penalty += $receive_amount_detail->late_charge - $receive_amount_detail->paid_late_charge - $receive_amount_detail->waive_late_charge;
                                //echo "interest: $receive_amount_detail->interest, paid_interest: $paid_interest <br>";
                            }
                            $outstanding_interest = $outstanding_i - $paid_interest;
                            $outstanding_delay_penalty = $outstanding_d- $paid_delay_penalty;
                            $last_receive_detail = $order->receive_records->last()->receive_amount_detail;
                            $outstanding_principal = $last_receive_detail->principal - $last_receive_detail->paid_principal ;
                            $outstanding_balance = $outstanding_principal + $outstanding_interest + $outstanding_delay_penalty;
                        }
                        $this_period_interest = $delay_penalty_and_interest['daily_interest'];
                        $total_interest = $outstanding_interest + $this_period_interest;

                        $this_period_delay_penalty = $delay_penalty_and_interest['delay_penalty'];
                        $total_delay_penalty = $outstanding_delay_penalty + $this_period_delay_penalty;
                    @endphp
                    <div class='font-bold'>Outstanding balance</div>
                    <div class='col-span-1 text-xl text-right' id='carry_over_outstanding_balance'>{{number_format($outstanding_balance,2)}}</div>
                    <div class='col-span-2'></div>
                    <div class='col-span-1 indent-8'>Outstanding Principal: </div>
                    <div class='col-span-1 text-right' id='outstanding_principal'>{{number_format($outstanding_principal,2)}}</div>
                    <div class='col-span-2'></div>
                    <div class='col-span-1 indent-8'>Outstanding Interest: </div>
                    <div class='col-span-1 text-right' id='carry_over_interest'> {{number_format($outstanding_interest,2)}}</div>
                    <div class='col-span-2'></div>
                    <div class='col-span-1 indent-8'>Outstanding Delay Penalty: </div> 
                    <div class='col-span-1 text-right' id='carry_over_delay_penalty'>{{number_format($outstanding_delay_penalty,2)}}</div>
                    <div class='col-span-2'></div>
                    <div class='font-bold'>Interest</div>
                    <div class='col-span-1 text-right text-xl' id='total_interest'>{{number_format($total_interest,2)}}</div>
                    <div class='col-span-2 pl-8'>
                        <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
                            <input
                                class="relative float-left mt-[0.15rem] mr-[6px] -ml-[1.5rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:ml-[0.25rem] checked:after:-mt-px checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-t-0 checked:after:border-l-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:ml-[0.25rem] checked:focus:after:-mt-px checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-t-0 checked:focus:after:border-l-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary"
                                type="checkbox"
                                value=""
                                id="exempt_interest" />
                            <label
                                class="inline-block pl-[0.15rem] hover:cursor-pointer"
                                for="exempt_interest">
                                Exempt all interest
                            </label>
                        </div>
                    </div>
                    <div class='col-span-1 indent-8'>Outstanding Interest: </div>
                    <div class='col-span-1 text-right' id='outstanding_interest'> {{number_format($outstanding_interest,2)}}</div>
                    <div class='col-span-2 pl-16'>
                        <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem] sr-only">
                            <input
                                class="relative float-left mt-[0.15rem] mr-[6px] -ml-[1.5rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:ml-[0.25rem] checked:after:-mt-px checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-t-0 checked:after:border-l-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:ml-[0.25rem] checked:focus:after:-mt-px checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-t-0 checked:focus:after:border-l-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary"
                                type="checkbox"
                                value=""
                                id="exempt_outstanding_interest" />
                            <label
                                class="inline-block pl-[0.15rem] hover:cursor-pointer"
                                for="exempt_outstanding_interest">
                                Exempt oustanding interest
                            </label>
                        </div>
                    </div>
                    <div class='col-span-1 indent-8'>This Period Interest: </div>
                    <div class='col-span-1 text-right' id='interest'> {{number_format($this_period_interest,2)}}</div>
                    <div class='col-span-2 pl-16'>
                        <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem] sr-only">
                            <input
                                class="relative float-left mt-[0.15rem] mr-[6px] -ml-[1.5rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:ml-[0.25rem] checked:after:-mt-px checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-t-0 checked:after:border-l-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:ml-[0.25rem] checked:focus:after:-mt-px checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-t-0 checked:focus:after:border-l-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary"
                                type="checkbox"
                                value=""
                                id="exempt_this_period_interest" />
                            <label
                                class="inline-block pl-[0.15rem] hover:cursor-pointer"
                                for="exempt_this_period_interest">
                                Exempt this period interest
                            </label>
                        </div>
                    </div>
                    <div class='font-bold'>Delay penalty</div>
                    <div class='col-span-1 text-right text-xl' id='total_delay_penalty'>{{number_format($total_delay_penalty,2)}}</div>
                    <div class='col-span-2 pl-8 content-center'>
                        <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
                            <input
                                class="relative float-left mt-[0.15rem] mr-[6px] -ml-[1.5rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:ml-[0.25rem] checked:after:-mt-px checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-t-0 checked:after:border-l-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:ml-[0.25rem] checked:focus:after:-mt-px checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-t-0 checked:focus:after:border-l-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary"
                                type="checkbox"
                                value=""
                                id="exempt_late_charge" />
                            <label
                                class="inline-block pl-[0.15rem] hover:cursor-pointer"
                                for="exempt_all_late_charge">
                                Exempt all late charge
                            </label>
                        </div>
                    </div>
                    <div class='col-span-1 indent-8'>Outstanding Delay Penalty: </div>
                    <div class='col-span-1 text-right' id='outstanding_delay_penalty'> {{number_format($outstanding_delay_penalty,2)}}</div>
                    <div class='col-span-2 pl-16 content-center'>
                        <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem] sr-only">
                            <input
                                class="relative float-left mt-[0.15rem] mr-[6px] -ml-[1.5rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:ml-[0.25rem] checked:after:-mt-px checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-t-0 checked:after:border-l-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:ml-[0.25rem] checked:focus:after:-mt-px checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-t-0 checked:focus:after:border-l-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary"
                                type="checkbox"
                                value=""
                                id="exempt_outstanding_late_charge" />
                            <label
                                class="inline-block pl-[0.15rem] hover:cursor-pointer"
                                for="exempt_all_late_charge">
                                Exempt outstanding late charge
                            </label>
                        </div>
                    </div>
                    <div class='col-span-1 indent-8'>This Period Delay Penalty: </div>
                    <div class='col-span-1 text-right' id='delay_penalty'> {{number_format($this_period_delay_penalty,2)}}</div>
                    <div class='col-span-2 pl-16 content-center'>
                        <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem] sr-only">
                            <input
                                class="relative float-left mt-[0.15rem] mr-[6px] -ml-[1.5rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:ml-[0.25rem] checked:after:-mt-px checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-t-0 checked:after:border-l-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:ml-[0.25rem] checked:focus:after:-mt-px checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-t-0 checked:focus:after:border-l-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary"
                                type="checkbox"
                                value=""
                                id="exempt_this_period_late_charge" />
                            <label
                                class="inline-block pl-[0.15rem] hover:cursor-pointer"
                                for="exempt_all_late_charge">
                                Exempt this period late charge
                            </label>
                        </div>
                    </div>
                    <div class='font-bold'>Partially received amount</div>
                    <div class='col-span-1 text-right text-xl' id='paid_total'>{{$order->receive_records ? number_format($order->receive_records->sum('receive_amount'),2) : 0.00}}</div>
                    <div class='col-span-2'></div>
                    <div class='col-span-1 indent-8'>Paid Principal: </div>
                    <div class='col-span-1 text-right' id='paid_principal'>{{number_format($paid_principal,2)}}</div>
                    <div class='col-span-2'></div>
                    <div class='col-span-1 indent-8'>Paid Interest: </div>
                    <div class='col-span-1 text-right' id='paid_interest'> {{number_format($paid_interest,2)}}</div>
                    <div class='col-span-2'></div>
                    <div class='col-span-1 indent-8'>Paid Delay Penalty: </div> 
                    <div class='col-span-1 text-right' id='paid_delay_penalty'>{{number_format($paid_delay_penalty,2)}}</div>
                    <div class='col-span-2'></div>
                    <div class='font-bold'>Partially paidback amount to supplier</div>
                    <div class='col-span-1 text-right text-xl' id='paidback_amount'>{{$order->receive_records ? number_format($order->receive_records->sum('net_pay_amount'),2) : 0.00}}</div>
                    <div class='col-span-2'></div>
                    <div class='font-bold'>Partially received dates</div>
                    <div class='col-span-3'>
                        @if(count($order->receive_records)!=0)
                            @php 
                                $dates = $order->receive_records->unique('receive_ymd')->pluck('receive_ymd')
                            @endphp
                            @foreach($dates as $date)
                                {{\Carbon\Carbon::parse($date)->isoFormat('DD MMMM YYYY') }}({{count($order->receive_records->where('receive_ymd',$date))}}),
                            @endforeach
                        @else - 
                        @endif
                    </div>
                    
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <div class="flex justify-left">
                            <div class="relative mb-3 w-full" data-te-input-wrapper-init>
                                <input
                                    id='receive_amount'
                                    type="text"
                                    class="peer block min-h-[auto] w-full rounded border-0 bg-transparent py-[0.32rem] px-3 leading-[2.15] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                                    id="exampleFormControlInput2"
                                    placeholder="Receive amount" />
                                <label
                                    for="exampleFormControlInput2"
                                    class="pointer-events-none absolute top-0 left-3 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[1.15rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[1.15rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-neutral-200"
                                    >Receive amount
                                </label>
                            </div>
                        </div>                      
                    </div>
                    <div class='col-span-2'>
                        <div class="flex justify-left">
                            <div class="relative mb-3 w-full" data-te-input-wrapper-init>
                                <textarea
                                    type="text"
                                    class="peer block min-h-[auto] w-full rounded border-0 bg-transparent py-[0.32rem] px-3 leading-[2.15] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                                    id="receive_comment"
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
                <x-button onclick='openModal()'>
                    Confirm
                </x-button>
            </div>
        </div>
    </div>
</x-app-layout>
<x-alert>Receive amount cannot be 0!</x-alert>
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
                    <div class="grid grid-cols-5 gap-1">
                        <!-- Input date -->
                        <div class='font-bold'>Input date</div>
                        <div class='col-span-1'>{{\Carbon\Carbon::parse($order->input_ymd)->isoFormat('DD MMMM YYYY')}}</div>
                        <!--  Receive scenario -->
                        <div class='font-bold row-span-3 col-span-1 uppercase text-center'>Scenario</div>
                        <div class='row-span-3 col-span-2 text-4xl' id='receive_scenario'>xxx</div>
                        <!-- Due date -->
                        <div class='font-bold'>Due date</div>
                        <div class='col-span-1'>{{\Carbon\Carbon::parse($order->installments->first()->due_ymd)->isoFormat('DD MMMM YYYY')}}</div>
                        <!-- Receive date-->
                        <div class='font-bold'>Receive date</div>
                        <div class='col-span-1' id='receive_date_to_confirm'>{{\Carbon\Carbon::now()->isoFormat('DD MMMM YYYY')}}</div>
                        <!-- Receive amount-->
                        <div class='font-bold'>Amount to receive</div>
                        <div class='col-span-4 text-xl text-center' id='receive_amount_to_confirm'>xxx</div>
                        <!--  Receive comment -->
                        <div class='font-bold'>Receive comment</div>
                        <div class='col-span-4' id='receive_comment_to_confirm'>xxx</div>
                        <div class='font-bold '>Amount</div>
                        <div class='text-xl text-right'>{{number_format($order->purchase_amount,2)}}</div>
                        <div class='font-bold border-l-4 border-indigo-500 pl-2'>Partially received amount</div>
                        <div class='col-span-1 text-xl text-right' >{{$order->receive_records ? number_format($order->receive_records->sum('receive_amount'),2) : 0.00}}</div>
                        <div class='text-xl font-bold text-right'>Balance</div>
                        @php
                            $paid_principal = 0;
                            $paid_interest = 0;
                            $paid_delay_penalty = 0;
                            $outstanding_principal = $order->purchase_amount;
                            $outstanding_interest = 0;
                            $outstanding_delay_penalty = 0;
                            $outstanding_balance = 0;
                            $total_interest = 0;
                            $total_delay_penalty = 0;
                            $this_period_interest = 0;
                            $this_period_delay_penalty=0;
                            if(count($order->receive_records)==0){
                                $delay_penalty_and_interest = $order->installments->first()->delayPenalty();
                                $outstanding_balance = $order->purchase_amount;   
                            }
                            else{
                                $delay_penalty_and_interest = $order->receive_records->last()->receive_amount_detail->delayPenalty();
                                $outstanding_i =$order->receive_records->last()->receive_amount_detail->interest;
                                $outstanding_d = $order->receive_records->last()->receive_amount_detail->late_charge;
                                //echo "outstanding_i:$outstanding_i,outstanding_d:$outstanding_d<br>";
                                foreach($order->receive_records as $record){
                                    $receive_amount_detail = $record->receive_amount_detail;
                                    $paid_principal += $receive_amount_detail->paid_principal;
                                    $paid_interest += $receive_amount_detail->paid_interest - $receive_amount_detail->waive_interest;
                                    $paid_delay_penalty += $receive_amount_detail->paid_late_charge - $receive_amount_detail->waive_late_charge;
                                    //$outstanding_interest += $receive_amount_detail->interest - $receive_amount_detail->paid_interest - $receive_amount_detail->waive_interest;
                                    //$outstanding_delay_penalty += $receive_amount_detail->late_charge - $receive_amount_detail->paid_late_charge - $receive_amount_detail->waive_late_charge;
                                    //echo "interest: $receive_amount_detail->interest, paid_interest: $paid_interest <br>";
                                }
                                $outstanding_interest = $outstanding_i - $paid_interest;
                                $outstanding_delay_penalty = $outstanding_d- $paid_delay_penalty;
                                $last_receive_detail = $order->receive_records->last()->receive_amount_detail;
                                $outstanding_principal = $last_receive_detail->principal - $last_receive_detail->paid_principal ;
                                $outstanding_balance = $outstanding_principal + $outstanding_interest + $outstanding_delay_penalty;
                            }
                            $this_period_interest = $delay_penalty_and_interest['daily_interest'];
                            $total_interest = $outstanding_interest + $this_period_interest;

                            $this_period_delay_penalty = $delay_penalty_and_interest['delay_penalty'];
                            $total_delay_penalty = $outstanding_delay_penalty + $this_period_delay_penalty;
                        @endphp
                        
                        <div></div><div></div><div class='border-l-4 border-indigo-500 pl-2 indent-4'>Paid Principal</div><div class='text-right'> {{number_format($paid_principal,2)}}</div>
                        <div></div><div></div><div></div><div class='border-l-4 border-indigo-500 pl-2 indent-4'>Paid Interest</div><div class='text-right'> {{number_format($paid_interest,2)}}</div>
                        <div></div><div></div><div></div><div class='border-l-4 border-indigo-500 pl-2 indent-4'>Paid Delay Penalty</div><div class='text-right'> {{number_format($paid_delay_penalty,2)}}</div>
                        
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                        <div class='font-bold '>Principal</div>
                        <div class='col-span-1 text-xl text-right'>{{number_format($outstanding_principal,2)}}</div>
                        <div class='font-bold border-l-4 border-indigo-500 pl-2'>Principal to pay</div>
                        <div class='col-span-1 text-xl text-right' id='principal_to_pay'>xxx</div>
                        <div class='col-span-1 text-xl text-right' id='principal_balance'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                        <div class='font-bold'>Interest</div>
                        <div class='col-span-1 text-xl text-right' id='total_interest'>{{number_format($total_interest,2)}}</div>
                        <div class='font-bold border-l-4 border-indigo-500 pl-2'>Interest to pay</div>
                        <div class='col-span-1 text-xl text-right' id='interest_to_pay'>xxx</div>
                        <div id='interest_balance' class='text-right text-xl'>xxxx</div>
                        <div class='pl-2 indent-4'>Outstanding interest</div>
                        <div class='text-right' id='outstanding_interest'>{{number_format($outstanding_interest,2)}}</div>
                        <div class='border-l-4 border-indigo-500 pl-2 indent-4'>Outstanding interest to pay</div>
                        <div class='text-right' id='outstanding_interest_to_pay'></div>
                        <div id='outstanding_interest_balance' class='text-right'>xxxx</div>
                        <div class='pl-2 indent-4'>This period interest</div>
                        <div class='text-right' id='interest'>{{number_format($this_period_interest,2)}}</div>
                        <div class='border-l-4 border-indigo-500 pl-2 indent-4'>This period interest to pay</div>
                        <div class='text-right' id='this_period_interest_to_pay'></div>
                        <div id='this_period_interest_balance' class='text-right'>xxxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                        <div class='font-bold'>Tax</div>
                        @php 
                            $tax =  $total_interest*0.01;
                            $tax_format = round($tax*100,2);
                        @endphp
                        <div class='col-span-1 text-xl text-right' id='tax'>{{number_format($tax_format,2)}}</div>
                        <div class='font-bold border-l-4 border-indigo-500 pl-2'>Tax to pay</div>
                        <div class='col-span-1 text-xl text-right' id='tax_to_pay'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                        <div class='font-bold'>Delay penalty</div>
                        <div class='col-span-1 text-xl text-right' id='total_delay_penalty'>{{number_format($total_delay_penalty,2)}}</div>
                        <div class='font-bold border-l-4 border-indigo-500 pl-2'>Delay penalty to pay</div>
                        <div class='col-span-1 text-xl text-right' id='delay_penalty_to_pay'>xxx</div>
                        <div class='text-right text-xl' id='delay_penalty_balance'>xxx</div>
                        <div class='pl-2 indent-4'>Outstanding delay penalty</div>
                        <div class='text-right' id='outstanding_delay_penalty'>{{number_format($outstanding_delay_penalty,2)}}</div>
                        <div class='border-l-4 border-indigo-500 pl-2 indent-4'>Outstanding delay penalty to pay</div>
                        <div class='text-right' id='outstanding_delay_penalty_to_pay'></div>
                        <div class='text-right' id='outstanding_delay_penalty_balance'>xxx</div>
                        <div class='pl-2 indent-4'>This period delay penalty</div>
                        <div class='text-right' id='delay_penalty'>{{number_format($this_period_delay_penalty,2)}}</div>
                        <div class='border-l-4 border-indigo-500 pl-2 indent-4'>This period delay penalty to pay</div>
                        <div class='text-right' id='this_period_delay_penalty_to_pay'></div>
                        <div class='text-right' id='this_period_delay_penalty_balance'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                        <div class='font-bold'>Interest + Delay penalty</div>
                        <div class='col-span-1 text-xl text-right' id='carry_over_outstanding_balance'>{{number_format($outstanding_interest + $outstanding_delay_penalty,2)}}</div>
                        <div class='border-l-4 border-indigo-500 pl-2 font-bold col-span-2'>Outstanding Interest + Delay penalty</div>
                        <div class='text-right text-xl ' id='this_time_outstanding'>xxx</div>
                        <div class='font-bold'>Carry over outstanding balance</div>
                        <div class='col-span-1 text-xl text-right' id='carry_over_outstanding_balance'>{{number_format($outstanding_balance,2)}}</div>
                        <div class='border-l-4 border-indigo-500 pl-2 font-bold col-span-2'>This time outstanding amount</div>
                        <div class='text-right text-xl ' id='this_time_outstanding'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                        <div class='font-bold col-span-2'>Payback amount to Supplier</div>
                        <div class='col-span-2 text-xl text-right' id='payback_amount_to_supplier'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                        <div class='font-bold'>Exceeded amount</div>
                        <div class='col-span-3 text-xl text-right' id='exceeded_amount'>xxx</div>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                    </div>
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
    class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
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
                    Receive amount detail new modal
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
                <div class="grid grid-cols-5 gap-1">
                    <!-- Input date -->
                    <div class='font-bold'>Input date</div>
                    <div class='col-span-1'>{{\Carbon\Carbon::parse($order->input_ymd)->isoFormat('DD MMMM YYYY')}}</div>
                    <!--  Receive scenario -->
                    <div class='font-bold row-span-3 col-span-1 uppercase text-center'>Scenario</div>
                    <div class='row-span-3 col-span-2 text-4xl' id='receive_scenario'>xxx</div>
                    <!-- Due date -->
                    <div class='font-bold'>Due date</div>
                    <div class='col-span-1'>{{\Carbon\Carbon::parse($order->installments->first()->due_ymd)->isoFormat('DD MMMM YYYY')}}</div>
                    <!-- Receive date-->
                    <div class='font-bold'>Receive date</div>
                    <div class='col-span-1' id='receive_date_to_confirm'>{{\Carbon\Carbon::now()->isoFormat('DD MMMM YYYY')}}</div>
                    <!-- Principal-->
                    <div class='font-bold'>Principal 100%</div>
                    <div class='col-span-1 text-xl text-right'>{{number_format($order->purchase_amount,2)}}</div>
                    <!-- Receive amount-->
                    <div class='font-bold pl-3'>Amount to receive</div>
                    <div class='col-span-1 text-xl text-center' id='receive_amount_to_confirm'>xxx</div>
                    <div class='col-span-1 text-xl text-center'></div>
                    <!-- Receive amount-->
                    <div class='font-bold'>Principal 10%</div>
                    <div class='col-span-1 text-xl text-right'>{{number_format($order->purchase_amount*0.1,2)}}</div>
                    
                    <!--  Receive comment -->
                    <div class='font-bold pl-3'>Receive comment</div>
                    <div class='col-span-2' id='receive_comment_to_confirm'>xxx</div>
                    <div class='font-bold '>Amount</div>
                    <div class='text-xl text-right'>{{number_format($order->purchase_amount,2)}}</div>
                    <div class='font-bold border-l-4 border-indigo-500 pl-2'>Partially received amount</div>
                    <div class='col-span-1 text-xl text-right' >{{$order->receive_records ? number_format($order->receive_records->sum('receive_amount'),2) : 0.00}}</div>
                    <div class='text-xl font-bold text-right'>Balance</div>
                    @php
                        $paid_principal = 0;
                        $paid_interest = 0;
                        $paid_delay_penalty = 0;
                        $outstanding_principal = $order->purchase_amount;
                        $outstanding_interest = 0;
                        $outstanding_delay_penalty = 0;
                        $outstanding_balance = 0;
                        $total_interest = 0;
                        $total_delay_penalty = 0;
                        $this_period_interest = 0;
                        $this_period_delay_penalty=0;
                        if(count($order->receive_records)==0){
                            $delay_penalty_and_interest = $order->installments->first()->delayPenalty();
                            $outstanding_balance = $order->purchase_amount;   
                        }
                        else{
                            $delay_penalty_and_interest = $order->receive_records->last()->receive_amount_detail->delayPenalty();
                            $outstanding_i =$order->receive_records->last()->receive_amount_detail->interest;
                            $outstanding_d = $order->receive_records->last()->receive_amount_detail->late_charge;
                            //echo "outstanding_i:$outstanding_i,outstanding_d:$outstanding_d<br>";
                            foreach($order->receive_records as $record){
                                $receive_amount_detail = $record->receive_amount_detail;
                                $paid_principal += $receive_amount_detail->paid_principal;
                                $paid_interest += $receive_amount_detail->paid_interest - $receive_amount_detail->waive_interest;
                                $paid_delay_penalty += $receive_amount_detail->paid_late_charge - $receive_amount_detail->waive_late_charge;
                                //$outstanding_interest += $receive_amount_detail->interest - $receive_amount_detail->paid_interest - $receive_amount_detail->waive_interest;
                                //$outstanding_delay_penalty += $receive_amount_detail->late_charge - $receive_amount_detail->paid_late_charge - $receive_amount_detail->waive_late_charge;
                                //echo "interest: $receive_amount_detail->interest, paid_interest: $paid_interest <br>";
                            }
                            $outstanding_interest = $outstanding_i - $paid_interest;
                            $outstanding_delay_penalty = $outstanding_d- $paid_delay_penalty;
                            $last_receive_detail = $order->receive_records->last()->receive_amount_detail;
                            $outstanding_principal = $last_receive_detail->principal - $last_receive_detail->paid_principal ;
                            $outstanding_balance = $outstanding_principal + $outstanding_interest + $outstanding_delay_penalty;
                        }
                        $this_period_interest = $delay_penalty_and_interest['daily_interest'];
                        $total_interest = $outstanding_interest + $this_period_interest;

                        $this_period_delay_penalty = $delay_penalty_and_interest['delay_penalty'];
                        $total_delay_penalty = $outstanding_delay_penalty + $this_period_delay_penalty;
                    @endphp
                    
                    <div></div><div></div><div class='border-l-4 border-indigo-500 pl-2 indent-4'>Paid Principal</div><div class='text-right'> {{number_format($paid_principal,2)}}</div>
                    <div></div><div></div><div></div><div class='border-l-4 border-indigo-500 pl-2 indent-4'>Paid Interest</div><div class='text-right'> {{number_format($paid_interest,2)}}</div>
                    <div></div><div></div><div></div><div class='border-l-4 border-indigo-500 pl-2 indent-4'>Paid Delay Penalty</div><div class='text-right'> {{number_format($paid_delay_penalty,2)}}</div>
                    
                    <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                    <div class='font-bold '>Principal</div>
                    <div class='col-span-1 text-xl text-right'>{{number_format($outstanding_principal,2)}}</div>
                    <div class='font-bold border-l-4 border-indigo-500 pl-2'>Principal to pay</div>
                    <div class='col-span-1 text-xl text-right' id='principal_to_pay'>xxx</div>
                    <div class='col-span-1 text-xl text-right' id='principal_balance'>xxx</div>
                    <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                    <div class='font-bold'>Delay penalty</div>
                    <div class='col-span-1 text-xl text-right' id='total_delay_penalty'>{{number_format($total_delay_penalty,2)}}</div>
                    <div class='font-bold border-l-4 border-indigo-500 pl-2'>Delay penalty to pay</div>
                    <div class='col-span-1 text-xl text-right' id='delay_penalty_to_pay'>xxx</div>
                    <div class='text-right text-xl' id='delay_penalty_balance'>xxx</div>
                    <div class='pl-2 indent-4'>Outstanding delay penalty</div>
                    <div class='text-right' id='outstanding_delay_penalty'>{{number_format($outstanding_delay_penalty,2)}}</div>
                    <div class='border-l-4 border-indigo-500 pl-2 indent-4'>Outstanding delay penalty to pay</div>
                    <div class='text-right' id='outstanding_delay_penalty_to_pay'></div>
                    <div class='text-right' id='outstanding_delay_penalty_balance'>xxx</div>
                    <div class='pl-2 indent-4'>This period delay penalty</div>
                    <div class='text-right' id='delay_penalty'>{{number_format($this_period_delay_penalty,2)}}</div>
                    <div class='border-l-4 border-indigo-500 pl-2 indent-4'>This period delay penalty to pay</div>
                    <div class='text-right' id='this_period_delay_penalty_to_pay'></div>
                    <div class='text-right' id='this_period_delay_penalty_balance'>xxx</div>
                    <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                    <div class='font-bold'>Interest</div>
                    <div class='col-span-1 text-xl text-right' id='total_interest'>{{number_format($total_interest,2)}}</div>
                    <div class='font-bold border-l-4 border-indigo-500 pl-2'>Interest to pay</div>
                    <div class='col-span-1 text-xl text-right' id='interest_to_pay'>xxx</div>
                    <div id='interest_balance' class='text-right text-xl'>xxxx</div>
                    <div class='pl-2 indent-4'>Outstanding interest</div>
                    <div class='text-right' id='outstanding_interest'>{{number_format($outstanding_interest,2)}}</div>
                    <div class='border-l-4 border-indigo-500 pl-2 indent-4'>Outstanding interest to pay</div>
                    <div class='text-right' id='outstanding_interest_to_pay'></div>
                    <div id='outstanding_interest_balance' class='text-right'>xxxx</div>
                    <div class='pl-2 indent-4'>This period interest</div>
                    <div class='text-right' id='interest'>{{number_format($this_period_interest,2)}}</div>
                    <div class='border-l-4 border-indigo-500 pl-2 indent-4'>This period interest to pay</div>
                    <div class='text-right' id='this_period_interest_to_pay'></div>
                    <div id='this_period_interest_balance' class='text-right'>xxxx</div>
                    <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                    <div class='font-bold'>Interest + Delay penalty</div>
                    <div class='col-span-1 text-xl text-right' id='interest_plus_delay_penalty'>{{number_format($total_interest + $total_delay_penalty,2)}}</div>
                    <div class='border-l-4 border-indigo-500 pl-2 font-bold col-span-1'>This time Interest + Delay pelalty</div>
                    <div class='text-right text-xl ' id='this_time_interest_plus_delay_penalty'>xxx</div>
                    <div class='text-right text-xl ' id='this_time_interest_plus_delay_penalty_balance'>xxx</div>
                    <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                    <div class='font-bold'>Tax</div>
                    @php 
                        $tax =  $total_interest*0.01;
                        $tax_format = round($tax,2);
                    @endphp
                    <div class='col-span-1 text-xl text-right' id='tax'>{{number_format($tax_format,2)}}</div>
                    <div class='font-bold border-l-4 border-indigo-500 pl-2'>Tax to pay</div>
                    <div class='col-span-1 text-xl text-right' id='tax_to_pay'>xxx</div>
                    <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                    <div class='font-bold'>Carry over outstanding balance</div>
                    <div class='col-span-1 text-xl text-right' id='carry_over_outstanding_balance'>{{number_format($outstanding_balance,2)}}</div>
                    <div class='border-l-4 border-indigo-500 pl-2 font-bold col-span-2'>This time outstanding amount</div>
                    <div class='text-right text-xl ' id='this_time_outstanding'>xxx</div>
                    <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                    <div class='font-bold col-span-2'>Payback amount to Supplier</div>
                    <div class='col-span-2 text-xl text-right' id='payback_amount_to_supplier'>xxx</div>
                    <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                    <div class='font-bold'>Exceeded amount</div>
                    <div class='col-span-3 text-xl text-right' id='exceeded_amount'>xxx</div>
                    <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                    <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700 col-span-5">
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button onclick="confirmRepayment()" data-te-modal-dismiss data-te-ripple-init type="button" class="text-white bg-green-700 hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 font-medium px-5 py-2.5 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Confirm repayment</button>
                <button onclick="closeMainModal()" data-te-modal-dismiss type="button" class="text-white bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 font-medium px-5 py-2.5 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
            </div>
        </div>
    </div>

</div>

<script>
    $('#exempt_late_charge').prop("checked", false);
    $('#exempt_outstanding_late_charge').prop("checked", false);
    $('#exempt_this_period_late_charge').prop("checked", false);
    $('#exempt_interest').prop("checked", false);
    $('#exempt_outstanding_interest').prop("checked", false);
    $('#exempt_this_period_interest').prop("checked", false);

    fd = new FormData();
    fd.append('order_id','{{$order->id}}')
    //default value
    fd.append('interest','{{$order->installments->first()->delayPenalty()['daily_interest']}}')
    fd.append('tax','{{floor($order->installments->first()->delayPenalty()['daily_interest']*0.01*100)/100}}')
    fd.append('delay_penalty','{{$order->installments->first()->delayPenalty()['delay_penalty']}}')
    fd.append('receive_date','{{\Carbon\Carbon::now()->isoFormat('YYYY-MM-DD')}}')

    const options = {
        format: "yyyy-mm-dd",
    };
    
    const datepickerEl = document.getElementById("myDatepicker");
    const datepicker = new te.Datepicker(datepickerEl,options);

    date_picked = '{{\Carbon\Carbon::now()->isoFormat('YYYY-MM-DD')}}'
    datepickerEl.addEventListener("dateChange.te.datepicker", (event) => {
        date_picked = date.value
        console.log('date.value: '+date.value)
        recalInterest(date.value)
    });
    const maxFracNF = new Intl.NumberFormat("en", {
        minimumFractionDigits: 2,
    });

    zero = maxFracNF.format(0)

    //check if repaydate is later than last payment or not
    @if(count($order->receive_records)==0)
        latest_repayment = '1900-01-01'
    @else
        latest_repayment = '{{$order->receive_records()->orderBy('id','desc')->first()->receive_ymd}}'
    @endif

    //console.log('latest_repayment: '+latest_repayment)
    const compareDates = (d1, d2) => {
        let date1 = new Date(d1).getTime();
        let date2 = new Date(d2).getTime();

        if (date1 < date2) {
            //console.log(`${d1} is less than ${d2}`);
            return 'Less'
        } else if (date1 > date2) {
            //console.log(`${d1} is greater than ${d2}`);
            return 'More'
        } else {
            //console.log(`Both dates are equal`);
            return 'Same'
        }
    };
    //console.log(compareDates(latest_repayment,'2023-04-20'))
    function recalInterest(date){
        //console.log(date)
        const d = new Date(date)
        const mediumTime = new Intl.DateTimeFormat("en-GB", {
                                day: "2-digit",
                                month: "long",
                                year: "numeric",
                            });
        //console.log('format date:'+mediumTime.format(d))
        $("[id='receive_date_to_confirm']").html(mediumTime.format(d))
        fd.append('receive_date',date)
        @if(count($order->receive_records)==0)
        url = 'cal_interest_and_delay?date='+date+'&installment_id={{$order->installments->first()->id}}'
        @else
        url = 'cal_interest_and_delay_partial?date='+date+'&order_id={{$order->id}}&installment_id={{$order->installments->first()->id}}'
        @endif
        //console.log(url)
        $.ajax({
            type: 'Get',
            url: url,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
                console.log(data)
                $("[id='interest']").html(maxFracNF.format(data['daily_interest']))
                outstanding_interest = parseFloat($("[id='outstanding_interest']").first().text().replace(/,/g, ''))
                total_interest = data['daily_interest']+outstanding_interest
                //console.log('outstanding interest: '+outstanding_interest)
                //console.log('total interest: '+total_interest)
                $("[id='total_interest']").html(maxFracNF.format(total_interest))
                date_diff_from_input = parseInt(data['date_diff_from_input'])
                $("[id='date_diff_from_last_due']").html(date_diff_from_input.toFixed())
                date_diff_from_due = parseInt(data['date_diff_from_due'])
                $("[id='date_diff_from_due']").html(date_diff_from_due.toFixed())
                tax = total_interest*.01
                tax_format = tax.toFixed(2)
                $("[id='tax']").html(maxFracNF.format(tax_format))
                outstanding_delay_penalty = parseFloat($("[id='outstanding_delay_penalty']").first().text().replace(/,/g, ''))
                if(!$('#exempt_late_charge').is(':checked')){   
                    total_delay_penalty = data['delay_penalty']+outstanding_delay_penalty
                    $("[id='total_delay_penalty']").html(maxFracNF.format(total_delay_penalty))
                    $("[id='delay_penalty']").html(maxFracNF.format(data['delay_penalty']))
                    interest_plus_delay_penalty = total_interest + total_delay_penalty
                    $("[id='interest_plus_delay_penalty']").html(maxFracNF.format(interest_plus_delay_penalty))
                    //console.log('delay penalty: '+data['delay_penalty'])
                }
                else{
                    $("[id='delay_penalty']").html(maxFracNF.format(0))
                    $("[id='total_delay_penalty']").html(maxFracNF.format(outstanding_delay_penalty))
                }
                fd.append('interest',data['daily_interest'])
                fd.append('tax',String(tax_format))     
                fd.append('delay_penalty',data['delay_penalty']) 
            },
            error: function(err){
                console.log(err)
            }
        })
    }

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

    function openModal(){
        clearFormData()
        console.log(compareDates(latest_repayment,date_picked))
        if(compareDates(latest_repayment,date_picked) == 'More'){
            message= `Date to repay (${date_picked}) cannot earlier than latest repayment date (${latest_repayment})`
            $("[id='final-confirm-button']").remove()
            $('#general_alert_content').text(message)
            alert_modal.show();
            return
        }
            
        receive_amount = $('#receive_amount').val()
        fd.append('receive_amount',receive_amount)
        fd.append('comment',$('#receive_comment').val())
        //console.log('late charge check box:'+$('#exempt_late_charge').is(':checked'))
        if(receive_amount.length > 0){
            receive_amount = parseFloat(receive_amount.replace(/,/g, ''))
            $("[id='receive_amount']").addClass('border-red-700')
            $("[id='receive_amount_to_confirm']").html(maxFracNF.format(receive_amount))
            purchase_amount = parseFloat('{{$order->purchase_amount}}')
            
            //console.log($("[id='interest']").first().text())
            tax = parseFloat($("[id='tax']").first().text().replace(/,/g, ''))
            //console.log('Tax:'+tax)
            //console.log('10% purchase amount:'+purchase_amount*0.1)
            ten_percent_buffer = parseFloat((purchase_amount*0.1).toFixed(2))
            cal_delay_penalty = adjustExemptLateChargeUI()

            console.log('cal_delay_penalty: '+cal_delay_penalty)

            daily_interest = adjustExemptInterestUI()

            console.log('daily_interest: '+daily_interest)

            outstanding = parseFloat($("[id='outstanding_principal']").first().text().replace(/,/g, ''))
            paid_total = parseFloat($("[id='paid_total']").first().text().replace(/,/g, ''))
            console.log(outstanding)
            //allocate receive amount
            allocateReceiveAmount(receive_amount,daily_interest,cal_delay_penalty,ten_percent_buffer,outstanding,paid_total)
            //console.log(payback_amount)
            //console.log($('#receive_comment').val())
            $("[id='receive_comment_to_confirm']").html($('#receive_comment').val())

            //modal.show()
            main_modal.show()
            
        }else{
            $("[id='final-confirm-button']").remove()
            $('#general_alert_content').text('Receive amount cannot be 0!')
            alert_modal.show()
        }
    }

    function adjustExemptLateChargeUI(){
        if($('#exempt_late_charge').is(':checked')){
            cal_delay_penalty = 0
            $("[id='delay_penalty_to_pay']").html(maxFracNF.format(0))
            $("[id='outstanding_delay_penalty_to_pay']").html(maxFracNF.format(0))
            $("[id='this_period_delay_penalty_to_pay']").html(maxFracNF.format(0))
            $("[id='delay_penalty_balance']").html(maxFracNF.format(0))
            $("[id='outstanding_delay_penalty_balance']").html(maxFracNF.format(0))
            $("[id='this_period_delay_penalty_balance']").html(maxFracNF.format(0))
        }
        else if($('#exempt_outstanding_late_charge').is(':checked')){
            cal_delay_penalty = parseFloat($("[id='outstanding_delay_penalty']").first().text().replace(/,/g, ''))
            $("[id='delay_penalty_to_pay']").html(maxFracNF.format(cal_delay_penalty))
            $("[id='outstanding_delay_penalty_to_pay']").html(maxFracNF.format(0))
            $("[id='this_period_delay_penalty_to_pay']").html(maxFracNF.format($('#delay_penalty').val()))
            $("[id='delay_penalty_balance']").html(maxFracNF.format(0))
            $("[id='outstanding_delay_penalty_balance']").html(maxFracNF.format(0))
            $("[id='this_period_delay_penalty_balance']").html(maxFracNF.format(0))
        }else if($('#exempt_this_period_late_charge').is(':checked')){
            cal_delay_penalty = parseFloat($("[id='delay_penalty']").first().text().replace(/,/g, ''))
            $("[id='delay_penalty_to_pay']").html(maxFracNF.format(cal_delay_penalty))
            $("[id='outstanding_delay_penalty_to_pay']").html(maxFracNF.format($('#outstanding_delay_penalty').val()))
            $("[id='this_period_delay_penalty_to_pay']").html(zero)
        }else{
            cal_delay_penalty = parseFloat($("[id='total_delay_penalty']").first().text().replace(/,/g, ''))
        }

        return cal_delay_penalty
    }

    function adjustExemptInterestUI(){
        if($('#exempt_interest').is(':checked')){
            daily_interest = 0
            $("[id='interest_to_pay']").html(zero)
            $("[id='outstanding_interest_to_pay']").html(zero)
            $("[id='this_period_interest_to_pay']").html(zero)
            $("[id='interest_balance']").html(zero)
            $("[id='outstanding_interest_balance']").html(zero)
            $("[id='this_period_interest_balance']").html(zero)
        }
        else if($('#exempt_outstanding_interest').is(':checked')){
            daily_interest = parseFloat($("[id='interest']").first().text().replace(/,/g, ''))
        }
        else if($('#exempt_this_period_interest').is(':checked')){
            daily_interest = parseFloat($("[id='this_period_interest']").first().text().replace(/,/g, ''))
        }
        else{
            daily_interest = parseFloat('{{$order->installments->first()->delayPenalty()['daily_interest']}}')
        }
        return daily_interest
    }

    function calInterest(){
        if($('#exempt_interest').is(':checked')){
            daily_interest = 0
            $("[id='interest_to_pay']").html(zero)
            $("[id='outstanding_interest_to_pay']").html(zero)
            $("[id='this_period_interest_to_pay']").html(zero)
            $("[id='interest_balance']").html(zero)
            $("[id='outstanding_interest_balance']").html(zero)
            $("[id='this_period_interest_balance']").html(zero)
            _interest_to_pay =  0
            _outstanding_interest_to_pay = 0
            _this_period_interest_to_pay = 0
            _interest_balance = 0
            _outstanding_interest_balance = 0
            _this_period_interest_balance = 0
        }
        else if($('#exempt_outstanding_interest').is(':checked')){
            daily_interest = parseFloat($("[id='interest']").first().text().replace(/,/g, ''))
        }
        else if($('#exempt_this_period_interest').is(':checked')){
            daily_interest = parseFloat($("[id='this_period_interest']").first().text().replace(/,/g, ''))
        }
        else{
            _interest_to_pay =  parseFloat($("[id='total_interest']").first().text().replace(/,/g, ''))
            _outstanding_interest_to_pay = parseFloat($("[id='outstanding_interest']").first().text().replace(/,/g, ''))
            _this_period_interest_to_pay = parseFloat($("[id='interest']").first().text().replace(/,/g, ''))
            _interest_balance = _interest_to_pay
            _outstanding_interest_balance = _outstanding_interest_to_pay
            _this_period_interest_balance = _this_period_interest_to_pay
            $("[id='interest_to_pay']").html($("[id='total_interest']").first().text())
            $("[id='outstanding_interest_to_pay']").html('-'+$("[id='outstanding_interest']").first().text())
            //$("[id='this_period_interest_to_pay']").html('-'+$("[id='interest']").first().text())
            $("[id='this_period_interest_to_pay']").html('abc')
            $("[id='interest_balance']").html($("[id='total_interest']").first().text())
            $("[id='outstanding_interest_balance']").html($("[id='outstanding_interest']").first().text())
            $("[id='this_period_interest_balance']").html($("[id='interest']").first().text())
        }
        
        return {
            interest_to_pay:_interest_to_pay,
            outstanding_interest_to_pay:_outstanding_interest_to_pay,
            this_period_interest_to_pay:_this_period_interest_to_pay,
            interest_balance:_interest_balance,
            outstanding_interest_balance:_outstanding_interest_balance,
            this_period_interest_balance:_this_period_interest_balance
        }
    }

    function calDelayPenalty(){
        if($('#exempt_late_charge').is(':checked')){
            cal_delay_penalty = 0
            $("[id='delay_penalty_to_pay']").html(zero)
            $("[id='outstanding_delay_penalty_to_pay']").html(zero)
            $("[id='this_period_delay_penalty_to_pay']").html(zero)
            $("[id='delay_penalty_balance']").html(zero)
            $("[id='outstanding_delay_penalty_balance']").html(zero)
            $("[id='this_period_delay_penalty_balance']").html(zero)
            _delay_penalty_to_pay =  0
            _outstanding_delay_penalty_to_pay = 0
            _this_period_delay_penalty_to_pay = 0
            _delay_penalty_balance = 0
            _outstanding_delay_penalty_balance = 0
            _this_period_delay_penalty_balance = 0
        }
        else if($('#exempt_outstanding_interest').is(':checked')){
            daily_interest = parseFloat($("[id='interest']").first().text().replace(/,/g, ''))
        }
        else if($('#exempt_this_period_interest').is(':checked')){
            daily_interest = parseFloat($("[id='this_period_interest']").first().text().replace(/,/g, ''))
        }
        else{
            _delay_penalty_to_pay =  parseFloat($("[id='total_delay_penalty']").first().text().replace(/,/g, ''))
            _outstanding_delay_penalty_to_pay = parseFloat($("[id='outstanding_delay_penalty']").first().text().replace(/,/g, ''))
            _this_period_delay_penalty_to_pay = parseFloat($("[id='delay_penalty']").first().text().replace(/,/g, ''))
            _delay_penalty_balance = _delay_penalty_to_pay
            _outstanding_delay_penalty_balance = _outstanding_delay_penalty_to_pay
            _this_period_delay_penalty_balance = _this_period_delay_penalty_to_pay
            $("[id='delay_penalty_to_pay']").html('-'+$("[id='total_delay_penalty']").first().text())
            $("[id='outstanding_delay_penalty_to_pay']").html('-'+$("[id='outstanding_delay_penalty']").first().text())
            $("[id='this_period_delay_penalty_to_pay']").html('-'+$("[id='delay_penalty']").first().text())
            $("[id='delay_penalty_balance']").html($("[id='total_delay_penalty']").first().text())
            $("[id='outstanding_delay_penalty_balance']").html('-'+$("[id='outstanding_delay_penalty']").first().text())
            $("[id='this_period_delay_penalty_balance']").html('-'+$("[id='delay_penalty']").first().text())
        }
        
        return {
            delay_penalty_to_pay:_delay_penalty_to_pay,
            outstanding_delay_penalty_to_pay:_outstanding_delay_penalty_to_pay,
            this_period_delay_penalty_to_pay:_this_period_delay_penalty_to_pay,
            delay_penalty_balance:_delay_penalty_balance,
            outstanding_delay_penalty_balance:_outstanding_delay_penalty_balance,
            this_period_delay_penalty_balance:_this_period_delay_penalty_balance
        }
    }

    //console.log(calInterest().interest_to_pay)
    function clearFormData(){
        fd.delete('receive_amount')
        fd.delete('comment')
        fd.delete('delay_penalty_to_pay')
        fd.delete('payback_amount_to_supplier')
        fd.delete('outstanding_balance')
        fd.delete('delay_penalty_to_pay')
        fd.delete('interest_to_pay')
        fd.delete('tax_to_pay')
    }

    function closeModal(){
        modal.hide();
    }

    function closeMainModal(){
        main_modal.hide();
    }

    function allocateReceiveAmount(receive_amount,interest_to_pay,delay_penalty,ten_percent_buffer,outstanding,paid_principal){
        //see material here https://siamsaison365.sharepoint.com/:p:/s/itsupport/EXvZVNLibzZPmsLuarwHHskBaZQNhgu9-_eB5h7L0zpoTQ?e=qBvqRD

        @php
            $paid_principal = 0;
            $paid_interest = 0;
            $paid_delay_penalty = 0;
            if(count($order->receive_records)==0)
                $delay_penalty_and_interest = $order->installments->first()->delayPenalty();
            else{
                $delay_penalty_and_interest = $order->receive_records->last()->receive_amount_detail->delayPenalty();
                foreach($order->receive_records as $record){
                    $paid_principal += $record->receive_amount_detail->paid_principal;
                    $paid_interest += $record->receive_amount_detail->paid_interest;
                    $paid_delay_penalty += $record->receive_amount_detail->paid_late_charge;
                }
            }
            $outstanding_balance_before = $delay_penalty_and_interest['outstanding']
        @endphp
        paid_interest = parseFloat('{{$paid_interest}}')
        paid_delay_penalty = parseFloat('{{$paid_delay_penalty}}')
        fd.append('outstanding_balance_before','{{$outstanding_balance_before}}')

        carry_over_outstanding_balance = parseFloat($("[id='carry_over_outstanding_balance']").first().text().replace(/,/g, ''))

        //this_period_delay_penalty = parseFloat($("[id='delay_penalty']").first().text().replace(/,/g, ''))
        //outstanding_delay_penalty = parseFloat($("[id='outstanding_delay_penalty']").first().text().replace(/,/g, ''))
        //console.log('this_period_delay_penalty: '+this_period_delay_penalty+'outstanding_delay_penalty: '+outstanding_delay_penalty)
        cal_delay_penalty_value = calDelayPenalty()
        console.log('cal_delay_penalty_value: '+JSON.stringify(cal_delay_penalty_value))
        delay_penalty = cal_delay_penalty_value.delay_penalty_to_pay
        this_period_delay_penalty = cal_delay_penalty_value.this_period_delay_penalty_to_pay
        outstanding_delay_penalty = cal_delay_penalty_value.outstanding_delay_penalty_to_pay
        
        //console.log('this_period_interest: '+this_period_interest+'outstanding_interest: '+outstanding_interest)
        cal_interest_value = calInterest()
        console.log('cal_interest_value: '+JSON.stringify(cal_interest_value))
        cal_interest = cal_interest_value.interest_to_pay
        this_period_interest = cal_interest_value.this_period_interest_to_pay
        outstanding_interest = cal_interest_value.outstanding_interest_to_pay
        //1 less than delay penalty
        outstanding_balance = outstanding
        payback_amount = 0
        if(receive_amount <= delay_penalty ){
            console.log('scenario  1')
            $("[id='delay_penalty_to_pay']").html(maxFracNF.format(receive_amount))
            fd.append('delay_penalty_to_pay',String(receive_amount))
            $("[id='payback_amount_to_supplier']").addClass('text-green-700').html(maxFracNF.format(0))
            fd.append('payback_amount_to_supplier','0')
            $("[id='principal_to_pay']").html(maxFracNF.format(0))
            $("[id='principal_balance']").html(maxFracNF.format(outstanding_balance))
            
            if(receive_amount > outstanding_delay_penalty){
                $("[id='receive_scenario']").html('1.1')
                $("[id='outstanding_delay_penalty_to_pay']").html(maxFracNF.format(outstanding_delay_penalty))
                $("[id='outstanding_delay_penalty_balance']").html(maxFracNF.format(0))
                balance = receive_amount - outstanding_delay_penalty
                $("[id='this_period_delay_penalty_to_pay']").html(maxFracNF.format(balance))
                this_period_delay_penalty_balance = this_period_delay_penalty - balance 
                $("[id='this_period_delay_penalty_balance']").html(maxFracNF.format(this_period_delay_penalty_balance))
                $("[id='delay_penalty_balance']").html(maxFracNF.format(this_period_delay_penalty_balance))
                
                
                $("[id='interest_to_pay']").html(zero)
                fd.append('interest_to_pay',String(0))
                //$("[id='outstanding_interest_to_pay']").html(maxFracNF.format(0))
                //$("[id='this_period_interest_to_pay']").html(maxFracNF.format(0))
                //$("[id='interest_balance']").html(maxFracNF.format(cal_interest.interest_balance))
                //$("[id='outstanding_interest_balance']").html(maxFracNF.format(cal_interest.outstanding_interest_balance))
                //$("[id='this_period_interest_balance']").html(maxFracNF.format(this_period_interest))
                $("[id='this_time_interest_plus_delay_penalty']").html(maxFracNF.format(this_period_interest + this_period_delay_penalty_balance))
                $("[id='this_time_outstanding']").html(maxFracNF.format(outstanding + delay_penalty - receive_amount + interest_to_pay))
                $("[id='tax_to_pay']").html(maxFracNF.format(0))
                fd.append('tax_to_pay',String(0))
                $("[id='outstanding_balance']").addClass('text-red-700').html(maxFracNF.format(outstanding_balance))
                $("[id='exceeded_amount']").addClass('text-red-700').html(maxFracNF.format(0))
                fd.append('outstanding_principal',String(outstanding_balance))
            }else{
                $("[id='receive_scenario']").html('1.2')
                $("[id='outstanding_delay_penalty_to_pay']").html(maxFracNF.format(receive_amount))
                $("[id='this_period_delay_penalty_to_pay']").html(maxFracNF.format(0))
                balance = delay_penalty - receive_amount
                $("[id='delay_penalty_balance']").html(maxFracNF.format(balance))
            }
            if($('#exempt_outstanding_late_charge').is(':checked')){
                $("[id='receive_scenario']").html('1.3')
                $("[id='this_period_delay_penalty_to_pay']").html(maxFracNF.format(receive_amount))
            }else if($('#exempt_this_period_late_charge').is(':checked')){
                $("[id='receive_scenario']").html('1.4')
                $("[id='outstanding_delay_penalty_to_pay']").html(maxFracNF.format(receive_amount))
            }
        }

        //2 less than delay penalty + interest
        console.log('2 receive_amount %f > delay_penalty %f && receive_amount %f <= delay_penalty %f + cal_interest %f',receive_amount,delay_penalty,receive_amount,delay_penalty,cal_interest)
        if(receive_amount > delay_penalty && receive_amount <= delay_penalty + cal_interest){
            console.log('scenario  2')
            $("[id='principal_to_pay']").html(maxFracNF.format(0))
            $("[id='principal_balance']").html(maxFracNF.format(0))
            //$("[id='outstanding_delay_penalty_to_pay']").html(maxFracNF.format(outstanding_delay_penalty))
            //$("[id='this_period_delay_penalty_to_pay']").html(maxFracNF.format(this_period_delay_penalty))
            //$("[id='outstanding_delay_penalty_balance']").html(maxFracNF.format(0))
            //$("[id='this_period_delay_penalty_balance']").html(maxFracNF.format(0))
            //$("[id='delay_penalty_balance']").html(maxFracNF.format(0))
            //$("[id='delay_penalty_to_pay']").html(maxFracNF.format(delay_penalty))
            fd.append('delay_penalty_to_pay',String(delay_penalty))
            $("[id='delay_penalty_balance']").html(zero)
            $("[id='outstanding_delay_penalty_balance']").html(zero)
            $("[id='this_period_delay_penalty_balance']").html(zero)
            interest_to_pay = receive_amount - delay_penalty
            $("[id='interest_to_pay']").html(maxFracNF.format(interest_to_pay))
            fd.append('interest_to_pay',String(interest_to_pay))
            tax = interest_to_pay*.01
            $("[id='tax_to_pay']").html(maxFracNF.format(Math.floor(tax*100)/100))
            fd.append('tax_to_pay',String(Math.floor(tax*100)/100))
            $("[id='payback_amount_to_supplier']").addClass('text-green-700').html(maxFracNF.format(0))
            fd.append('payback_amount_to_supplier','0')
            console.log('receive_amount %d> delay_penalty %d + outstanding_interest %d',receive_amount ,delay_penalty,outstanding_interest)
            if(receive_amount > delay_penalty + outstanding_interest){
                $("[id='receive_scenario']").html('2.1')
                $("[id='outstanding_interest_to_pay']").html(maxFracNF.format(outstanding_interest))
                balance = receive_amount - delay_penalty - outstanding_interest
                $("[id='this_period_interest_to_pay']").html(maxFracNF.format(balance))
                interest_balance = cal_interest - balance
                $("[id='interest_balance']").html(maxFracNF.format(interest_balance))
                $("[id='outstanding_interest_balance']").html(maxFracNF.format(outstanding_interest))
                this_period_interest_balance = this_period_interest - balance
                $("[id='this_period_interest_balance']").html(maxFracNF.format(this_period_interest_balance))
                //console.log('outstanding %f - (receive_amount %f - delay_penalty %f - cal_interest %f)',outstanding,receive_amount,delay_penalty,cal_interest)
                this_time_oustanding = outstanding - (receive_amount - delay_penalty - cal_interest)
                $("[id='this_time_outstanding']").html(maxFracNF.format(outstanding - (receive_amount - delay_penalty - cal_interest)))
                fd.append('outstanding_principal',String(outstanding))
                $("[id='this_time_interest_plus_delay_penalty']").html(maxFracNF.format(this_period_interest_balance))
                
                $("[id='exceeded_amount']").addClass('text-red-700').html(zero)
            }else{
                $("[id='receive_scenario']").html('2.2')
                $("[id='outstanding_interest_to_pay']").html(maxFracNF.format(receive_amount-delay_penalty))
                $("[id='this_period_interest_to_pay']").html(maxFracNF.format(0))
                balance = outstanding_interest - (receive_amount - delay_penalty)
                $("[id='outstanding_interest_balance']").html(maxFracNF.format(balance))
                $("[id='this_period_interest_balance']").html(maxFracNF.format(this_period_interest))
                interest_balance = cal_interest - (receive_amount - delay_penalty)
                $("[id='interest_balance']").html(maxFracNF.format(interest_balance))
                $("[id='this_time_interest_plus_delay_penalty']").html(maxFracNF.format(interest_balance))
                this_time_oustanding = carry_over_outstanding_balance - (receive_amount - delay_penalty - cal_interest)
                console.log('this_time_oustanding %f = carry_over_outstanding_balance %f - (receive_amount %f - delay_penalty %f - cal_interest) %f',this_time_oustanding,carry_over_outstanding_balance,receive_amount,delay_penalty,cal_interest)
                $("[id='this_time_outstanding']").html(maxFracNF.format(this_time_oustanding))
                fd.append('outstanding_principal',String(outstanding))
                $("[id='exceeded_amount']").addClass('text-red-700').html(zero)
            }
        }

        //3 more than delay penalty + interest 
        console.log('3 receive_amount %d > delay_penalty %d+ cal_interest %d',receive_amount,delay_penalty,cal_interest)
        if(receive_amount > delay_penalty + cal_interest){
            console.log('scenario  3')
            $("[id='receive_scenario']").html(3)
            $("[id='outstanding_interest_to_pay']").html(maxFracNF.format(outstanding_interest))
            $("[id='this_period_interest_to_pay']").html(maxFracNF.format(this_period_interest))
            $("[id='outstanding_delay_penalty_to_pay']").html(maxFracNF.format(outstanding_delay_penalty))
            $("[id='this_period_delay_penalty_to_pay']").html(maxFracNF.format(this_period_delay_penalty))
            $("[id='interest_balance']").html(zero)
            $("[id='outstanding_interest_balance']").html(zero)
            $("[id='this_period_interest_balance']").html(zero)
            $("[id='delay_penalty_balance']").html(zero)
            $("[id='outstanding_delay_penalty_balance']").html(zero)
            $("[id='this_period_delay_penalty_balance']").html(zero)
            $("[id='this_time_outstanding']").html(zero)
            $("[id='delay_penalty_to_pay']").html(maxFracNF.format(delay_penalty))
            
            fd.append('delay_penalty_to_pay',String(delay_penalty))
            $("[id='interest_to_pay']").html(maxFracNF.format(cal_interest))
            fd.append('interest_to_pay',String(cal_interest))
            tax = cal_interest*.01
            $("[id='tax_to_pay']").html(maxFracNF.format(Math.floor(tax*100)/100))
            fd.append('tax_to_pay',String(Math.floor(tax*100)/100))

            balance = receive_amount - delay_penalty - cal_interest
            $("[id='principal_to_pay']").html(maxFracNF.format(balance))
            outstanding_balance = outstanding - balance
            $("[id='principal_balance']").html(maxFracNF.format(outstanding_balance))
            $("[id='this_time_outstanding']").html(maxFracNF.format(outstanding_balance))
  
            $("[id='payback_amount_to_supplier']").addClass('text-green-700').html(maxFracNF.format(payback_amount))
            fd.append('payback_amount_to_supplier',String(payback_amount))
            
            $("[id='this_time_interest_plus_delay_penalty']").html(zero)
            $("[id='outstanding_balance']").addClass('text-red-700').html(maxFracNF.format(outstanding_balance))
            fd.append('outstanding_principal',String(outstanding_balance))
            $("[id='exceeded_amount']").addClass('text-red-700').html(zero)
        }

        //4 less than delay penalty + interest + outstanding
        console.log('4 receive_amount %d > cal_interest %d + delay_penalty %d && receive_amount %d <= outstanding %d + cal_interest %d+ delay_penalty %d',
        receive_amount,cal_interest,delay_penalty,receive_amount, outstanding,cal_interest, delay_penalty)
        if(receive_amount > cal_interest + delay_penalty && receive_amount <= outstanding + cal_interest + delay_penalty){
            console.log('scenario  4')
            $("[id='receive_scenario']").html(44)
            $("[id='delay_penalty_to_pay']").html(maxFracNF.format(delay_penalty))
            fd.append('delay_penalty_to_pay',String(delay_penalty))
            $("[id='interest_to_pay']").html(maxFracNF.format(cal_interest))
            fd.append('interest_to_pay',String(cal_interest))
            tax = cal_interest*.01
            $("[id='tax_to_pay']").html(maxFracNF.format(Math.floor(tax*100)/100))
            fd.append('tax_to_pay',String(Math.floor(tax*100)/100))
            payback_amount = 0
            $("[id='payback_amount_to_supplier']").addClass('text-green-700').html(maxFracNF.format(payback_amount))
            fd.append('payback_amount_to_supplier',String(payback_amount))
            balance = receive_amount - delay_penalty - cal_interest
            outstanding_balance = outstanding - balance
            console.log('outstanding_balance %d = outstanding %d - receive_amount %d',outstanding_balance,outstanding,receive_amount)
            $("[id='outstanding_balance']").addClass('text-red-700').html(maxFracNF.format(outstanding_balance))
            fd.append('outstanding_principal',String(outstanding_balance))
            $("[id='exceeded_amount']").addClass('text-red-700').html(zero)
            $("[id='this_time_interest_plus_delay_penalty']").html(zero)
        }

        //5 more than delay penalty + interest + outstanding
        if(receive_amount > outstanding + cal_interest + delay_penalty){
            console.log('scenario  5')
            $("[id='receive_scenario']").html(5)
            $("[id='delay_penalty_to_pay']").html(maxFracNF.format(delay_penalty))
            fd.append('delay_penalty_to_pay',String(delay_penalty))
            $("[id='interest_to_pay']").html(maxFracNF.format(cal_interest))
            $("[id='principal_to_pay']").html(maxFracNF.format(outstanding))
            $("[id='principal_balance']").html(zero)
            $("[id='this_time_outstanding']").html(zero)
            fd.append('interest_to_pay',String(cal_interest))
            tax = cal_interest*.01
            $("[id='tax_to_pay']").html(maxFracNF.format(Math.floor(tax*100)/100))
            fd.append('tax_to_pay',String(Math.floor(tax*100)/100))
            payback_amount = ten_percent_buffer -delay_penalty - cal_interest + Math.floor(tax*100)/100
            console.log('ten_percent_buffer: %f,daily_interest:%f,delay_penalty:%f, Math.floor(tax*100)/100:%f,paid_principal:%f',ten_percent_buffer,daily_interest,delay_penalty,Math.floor(tax*100)/100,paid_principal)
            $("[id='payback_amount_to_supplier']").addClass('text-green-700').html(maxFracNF.format(payback_amount))
            fd.append('payback_amount_to_supplier',String(payback_amount))
            exceeded_amount = receive_amount - outstanding - cal_interest - delay_penalty
            $("[id='outstanding_balance']").addClass('text-red-700').html(zero)
            fd.append('outstanding_principal',String(0))
            $("[id='exceeded_amount']").addClass('text-red-700').html(maxFracNF.format(exceeded_amount))
            fd.append('exceeded_amount',String(exceeded_amount))
        }

        //6 receive_amount = outstanding + delay_penalty + interest
        console.log('6 receive_amount %d == outstanding %d && delay_penalty %d + cal_interest %d < ten_percent_buffer %d',receive_amount,outstanding,delay_penalty,cal_interest,ten_percent_buffer)
        if(receive_amount == outstanding && delay_penalty + cal_interest < ten_percent_buffer){
            console.log('scenario  6')
            $("[id='receive_scenario']").html(6)
            $("[id='delay_penalty_to_pay']").html('-'+maxFracNF.format(delay_penalty))
            $("[id='this_period_delay_penalty_to_pay']").html('-'+maxFracNF.format(this_period_delay_penalty))
            $("[id='outstanding_delay_penalty_to_pay']").html('-'+maxFracNF.format(outstanding_delay_penalty))
            fd.append('delay_penalty_to_pay',String(delay_penalty))
            $("[id='interest_to_pay']").html('-'+maxFracNF.format(cal_interest))
            $("[id='this_period_interest_to_pay']").html('-'+maxFracNF.format(this_period_interest))
            $("[id='outstanding_interest_to_pay']").html('-'+maxFracNF.format(outstanding_interest))
            $("[id='principal_to_pay']").html('-'+maxFracNF.format(outstanding))
            $("[id='this_time_interest_plus_delay_penalty']").html('-'+maxFracNF.format(cal_interest+delay_penalty))
            $("[id='this_time_interest_plus_delay_penalty_balance']").html(zero)
            
            $("[id='principal_balance']").html(zero)
            $("[id='this_time_outstanding']").html(zero)
            fd.append('interest_to_pay',String(cal_interest)) 
            tax = (cal_interest*.01).toFixed(2)
            console.log('tax: '+tax)
            $("[id='tax_to_pay']").html(maxFracNF.format(tax)) //
            fd.append('tax_to_pay',String(tax))
            payback_amount = ten_percent_buffer -delay_penalty - cal_interest + parseFloat(tax)
            console.log('ten_percent_buffer: %f,daily_interest:%f,delay_penalty:%f, tax:%f,paid_principal:%f',ten_percent_buffer,daily_interest,delay_penalty,tax,paid_principal)
            $("[id='payback_amount_to_supplier']").addClass('text-green-700').html(maxFracNF.format(payback_amount))
            fd.append('payback_amount_to_supplier',String(payback_amount))
            exceeded_amount = receive_amount - outstanding - cal_interest - delay_penalty
            $("[id='outstanding_balance']").addClass('text-red-700').html(zero)
            fd.append('outstanding_principal',String(0))
            $("[id='exceeded_amount']").addClass('text-red-700').html(zero)
            fd.append('exceeded_amount',String(0))
        }
        console.log('payback_amount: %f,ten_percent_buffer: %f,daily_interest:%f,delay_penalty:%f, tax:%f,paid_principal:%f',payback_amount,ten_percent_buffer,daily_interest,delay_penalty,Math.floor(tax*100)/100,paid_principal)
        console.log(fd)
    }

    function confirmRepayment(){
        main_modal.hide()
        $("[id='final-confirm-button']").remove()
        $('#general_alert_content').text('This action cannot be undone!')
        confirm_button = `<button id="final-confirm-button" type="button" onclick='finalConfirmRepayment()' class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-400 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">Confirm</button>`
        $('#alert-footer').append(confirm_button)
        alert_modal.show();     
    }
    
    function finalConfirmRepayment(){
        console.log('to confirm repayment')
        modal.hide()
        fd.append('_token','{{csrf_token()}}')
        fd.append('is_exempt_late_charge',$('#exempt_late_charge').is(':checked') ? '1' : '0')
        fd.append('is_exempt_late_charge',$('#exempt_late_charge').is(':checked') ? '1' : '0')
        processing_modal.show()
        $.ajax({
            type: 'Post',
            url: 'store/receive_amount',
            cache: false,
            contentType: false,
            processData: false,
            data : fd,
            success: function(data){
                //processing_modal.hide()
                console.log(data)
                window.location = '{{url('/unpaidup_orders')}}'
            },
            error: function(err){
                alert(err)
            }
        })
    }

    $('#exempt_late_charge').change(function() {
        if(this.checked) {
            var returnVal = confirm("Are you sure to exempt late charge?");
            $(this).prop("checked", returnVal);
            fd.append('is_exempt_late_charge','1')
            //$("[id='delay_penalty']").html(maxFracNF.format(0))
            $("[id='delay_penalty_to_pay']").addClass('bg-red-400 text-white')
            $("[id='outstanding_delay_penalty_to_pay']").addClass('bg-red-200 text-white')
            $("[id='this_period_delay_penalty_to_pay']").addClass('bg-red-200 text-white')
            $("[id='delay_penalty']").addClass('line-through')
            $("[id='total_delay_penalty']").addClass('line-through')
            $("[id='outstanding_delay_penalty']").addClass('line-through')
        }else{
            fd.append('is_exempt_late_charge','0')
            $("[id='delay_penalty_to_pay']").removeClass('bg-red-400 text-white')
            $("[id='delay_penalty']").removeClass('line-through')
            $("[id='total_delay_penalty']").removeClass('line-through')
            $("[id='outstanding_delay_penalty']").removeClass('line-through')
            $("[id='outstanding_delay_penalty_to_pay']").removeClass('bg-red-200 text-white')
            $("[id='this_period_delay_penalty_to_pay']").removeClass('bg-red-200 text-white')
        }
        //$('#textbox1').val(this.checked);        
    });

    $('#exempt_outstanding_late_charge').change(function() {
        clearWaiveDelayPenaltyCheckStatus()
        if(this.checked) {
            var returnVal = confirm("Are you sure to exempt late charge?");
            $(this).prop("checked", returnVal);
            fd.append('is_exempt_outstanding_late_charge','1')
            //$("[id='delay_penalty']").html(maxFracNF.format(0))
            $("[id='delay_penalty_to_pay']").addClass('bg-red-400 text-white')
            $("[id='outstanding_delay_penalty_to_pay']").addClass('bg-red-200 text-white')
            $("[id='outstanding_delay_penalty']").addClass('line-through')
            if($('#exempt_this_period_late_charge').is(':checked')){
                $("[id='exempt_late_charge']").prop('checked',true)
                $("[id='total_delay_penalty']").addClass('line-through')
                $("[id='delay_penalty']").addClass('line-through')
                $("[id='exempt_outstanding_late_charge']").prop('checked',false)
                $("[id='exempt_this_period_late_charge']").prop('checked',false)
            }
        }else{
            fd.append('is_exempt_outstanding_late_charge','0')
            $("[id='delay_penalty_to_pay']").removeClass('bg-red-400 text-white')
            $("[id='outstanding_delay_penalty_to_pay']").removeClass('bg-red-200 text-white')
            /* $("[id='delay_penalty']").removeClass('line-through')
            $("[id='total_delay_penalty']").removeClass('line-through')
            $("[id='outstanding_delay_penalty']").removeClass('line-through') */
        }
        //$('#textbox1').val(this.checked);        
    });

    $('#exempt_this_period_late_charge').change(function() {
        clearWaiveDelayPenaltyCheckStatus()
        if(this.checked) {
            var returnVal = confirm("Are you sure to exempt late charge?");
            $(this).prop("checked", returnVal);
            fd.append('is_exempt_this_period_late_charge','1')
            //$("[id='delay_penalty']").html(maxFracNF.format(0))
            $("[id='delay_penalty_to_pay']").addClass('bg-red-400 text-white')
            $("[id='this_period_delay_penalty_to_pay']").addClass('bg-red-200 text-white')
            $("[id='delay_penalty']").addClass('line-through')
            if($('#exempt_outstanding_late_charge').is(':checked')){
                $("[id='exempt_late_charge']").prop('checked',true)
                $("[id='total_delay_penalty']").addClass('line-through')
                $("[id='outstanding_delay_penalty']").addClass('line-through')
                $("[id='exempt_outstanding_late_charge']").prop('checked',false)
                $("[id='exempt_this_period_late_charge']").prop('checked',false)
            }
        }else{
            fd.append('is_exempt_this_period_late_charge','0')
            $("[id='delay_penalty_to_pay']").removeClass('bg-red-400 text-white')
            $("[id='this_period_delay_penalty_to_pay']").removeClass('bg-red-200 text-white')
            /* $("[id='delay_penalty']").removeClass('line-through')
            $("[id='total_delay_penalty']").removeClass('line-through')
            $("[id='outstanding_delay_penalty']").removeClass('line-through') */
        }
        //$('#textbox1').val(this.checked);        
    });

    function clearWaiveDelayPenaltyCheckStatus(){
        $("[id='exempt_late_charge']").prop('checked',false)
        $("[id='delay_penalty']").removeClass('line-through')
        $("[id='total_delay_penalty']").removeClass('line-through')
        $("[id='outstanding_delay_penalty']").removeClass('line-through')
    }

    $('#exempt_interest').change(function() {
        if(this.checked) {
            var returnVal = confirm("Are you sure to exempt interest?");
            $(this).prop("checked", returnVal);
            fd.append('is_exempt_interest','1')
            $("[id='interest_to_pay']").html(maxFracNF.format(0))
            $("[id='tax_to_pay']").html(maxFracNF.format(0))
            //$("[id='tax']").html(maxFracNF.format(0))
            $("[id='tax_to_pay']").addClass('bg-red-400 text-white')
            $("[id='interest_to_pay']").addClass('bg-red-400 text-white')
            $("[id='outstanding_interest_to_pay']").addClass('bg-red-200 text-white')
            $("[id='this_period_interest_to_pay']").addClass('bg-red-200 text-white')
            $("[id='interest']").addClass('line-through')
            $("[id='total_interest']").addClass('line-through')
            $("[id='outstanding_interest']").addClass('line-through')
        }else{
            fd.append('is_exempt_interest','0')
            $("[id='tax_to_pay']").removeClass('bg-red-400 text-white')
            $("[id='interest_to_pay']").removeClass('bg-red-400 text-white')
            $("[id='interest']").removeClass('line-through')
            $("[id='total_interest']").removeClass('line-through')
            $("[id='outstanding_interest']").removeClass('line-through')
            $("[id='outstanding_interest_to_pay']").removeClass('bg-red-200 text-white')
            $("[id='this_period_interest_to_pay']").removeClass('bg-red-200 text-white')
        }
        //$('#textbox1').val(this.checked);        
    });

    $('#exempt_outstanding_interest').change(function() {
        clearWaiveInterestCheckStatus()
        if(this.checked) {
            var returnVal = confirm("Are you sure to exempt interest?");
            $(this).prop("checked", returnVal);
            $("[id='exempt_interest']").prop('checked',false)
            fd.append('is_exempt_outstanding_interest','1')
            $("[id='interest_to_pay']").html(maxFracNF.format(0))
            $("[id='tax_to_pay']").html(maxFracNF.format(0))
            //$("[id='tax']").html(maxFracNF.format(0))
            $("[id='tax_to_pay']").addClass('bg-red-400 text-white')
            $("[id='interest_to_pay']").addClass('bg-red-400 text-white')
            $("[id='outstanding_interest']").addClass('line-through')
            if($('#exempt_this_period_interest').is(':checked')){
                $("[id='exempt_interest']").prop('checked',true)
                $("[id='total_interest']").addClass('line-through')
                $("[id='interest']").addClass('line-through')
                $("[id='exempt_outstanding_interest']").prop('checked',false)
                $("[id='exempt_this_period_interest']").prop('checked',false)
            }
        }else{
            fd.append('is_exempt_outstanding_interest','0')
            $("[id='tax_to_pay']").removeClass('bg-red-400 text-white')
            $("[id='interest_to_pay']").removeClass('bg-red-400 text-white')
            /* $("[id='interest']").removeClass('line-through')
            $("[id='total_interest']").removeClass('line-through')
            $("[id='outstanding_interest']").removeClass('line-through') */
        }
        //$('#textbox1').val(this.checked);        
    });

    $('#exempt_this_period_interest').change(function() {
        clearWaiveInterestCheckStatus()
        if(this.checked) {
            var returnVal = confirm("Are you sure to exempt interest?");
            $(this).prop("checked", returnVal);
            $("[id='exempt_interest']").prop('checked',false)
            fd.append('is_exempt_this_period_interest','1')
            $("[id='interest_to_pay']").html(maxFracNF.format(0))
            $("[id='tax_to_pay']").html(maxFracNF.format(0))
            //$("[id='tax']").html(maxFracNF.format(0))
            $("[id='tax_to_pay']").addClass('bg-red-400 text-white')
            $("[id='interest_to_pay']").addClass('bg-red-400 text-white')
            $("[id='interest']").addClass('line-through')
            if($('#exempt_outstanding_interest').is(':checked')){
                $("[id='exempt_interest']").prop('checked',true)
                $("[id='total_interest']").addClass('line-through')
                $("[id='outstanding_interest']").addClass('line-through')
                $("[id='exempt_outstanding_interest']").prop('checked',false)
                $("[id='exempt_this_period_interest']").prop('checked',false)
            }
        }else{
            fd.append('is_exempt_this_period_interest','0')
            $("[id='tax_to_pay']").removeClass('bg-red-400 text-white')
            $("[id='interest_to_pay']").removeClass('bg-red-400 text-white')
           /*  $("[id='interest']").removeClass('line-through')
            $("[id='total_interest']").removeClass('line-through')
            $("[id='outstanding_interest']").removeClass('line-through') */
        }
        //$('#textbox1').val(this.checked);        
    });

    function clearWaiveInterestCheckStatus(){
        $("[id='interest']").removeClass('line-through')
        $("[id='total_interest']").removeClass('line-through')
        $("[id='outstanding_interest']").removeClass('line-through')
    }
</script>
