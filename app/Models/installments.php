<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class installments extends Model
{
    use HasFactory;
    protected $fillable =[
        'customer_id',
        'order_id',
        'installment_number',
        'due_ymd',
        'principal',
        'interest',
        'paid_principal',
        'paid_interest',
        'paid_late_charge',
    ];

    public function order(){
        return $this->belongsTo(order::class);
    }

    public function contractor(){
        return $this->belongsTo(contractors::class);
    }
    
    public function receive_amount_details(){
        return $this->hasMany(ReceiveAmountDetail::class, 'installment_id', 'id');
    }

    public function delayPenaltyRate(){
        // return $this->order->contractor->delay_penalty_rate;
        return $this->order->product_offering->delay_penalty_rate;
    }

    public function interestRate(){
        return $this->order->product_offering->interest_rate;
    }
    
    public function discountRate(){
        return $this->order->product_offering->discount_rate;
    }

    public function getDelayPenaltyAttribute($date){
        return "To calculate delay penalty on $date";
    }

    public function payment(){
        return $this->belongsTo(payments::class);
    }

    public function scopeDelayPenalty($query){
        return $query->whereNotNull('paid_up_ymd');
    }

    public function calAccruInterestAndDelayPenalty($date){
        $cal_date = Carbon::createFromFormat('Y-m-d',$date);
        $details = $this->receive_amount_details;
        if($details->count() == 0){ //no receive history yet first repayment
            //return $this->interestWithDate($date);
            $last_receive_date = Carbon::createFromFormat('Ymd',$this->order->purchase_ymd);
            $date_diff_from_last_receive = $cal_date->diffInDays($last_receive_date);
        }else{
            $last_receive_date = Carbon::createFromFormat('Y-m-d',$details->last()?->receive_history->receive_ymd)->startOfDay();
            $date_diff_from_last_receive = $cal_date->diffInDays($last_receive_date);
        }

        //not a first payment
        $interest_rate = $this->interestRate();
        //return $interest_rate;
        $discount_rate = $this->discountRate();
        $delay_penalty_rate = $this->delayPenaltyRate();
        $due_ymd = $this->due_ymd;
        $is_delay = ($cal_date->copy()->isoFormat('YYYYMMDD') > $due_ymd && is_null($this->paid_up_ymd)) ? "Yes" : 'No';
        $principal_balance = $this->principal - $this->paid_principal;
        $delay_penalty_cal =0;
        $date_diff_from_due = $cal_date->copy()->diffInDays($due_ymd);
        $term = $this->order->product_offering->product->terms;
        if($is_delay == 'Yes'){
            $effective_interest_rate = $interest_rate;
            $interest_before_due = $interest_rate - $discount_rate;
            
            if($details->count() == 0){ //no payment yet
                $delay_penalty_cal = $principal_balance*$delay_penalty_rate/100*($date_diff_from_due)/365;
                $delay_penalty_cal = floor($delay_penalty_cal*100)/100;
                $daily_interest_before_due = $principal_balance*$interest_before_due/100*($term)/365;
                $daily_interest_after_due = $principal_balance*$effective_interest_rate/100*($date_diff_from_due)/365;
                $daily_interest_cal = floatval($daily_interest_before_due) + floatval($daily_interest_after_due);
                $daily_interest_cal = floor($daily_interest_cal*100)/100;
            }else{ //has partial payment
                $delay_penalty_cal = $principal_balance*$delay_penalty_rate/100*($date_diff_from_last_receive)/365;
                $delay_penalty_cal = floor($delay_penalty_cal*100)/100;
                //partial payment within due
                if($last_receive_date->copy()->isoFormat('YYYYMMDD') <= $due_ymd){
                    $date_diff_from_last_receive_to_due = $last_receive_date->copy()->diffInDays($due_ymd);
                    $daily_interest_before_due = $principal_balance*$interest_before_due/100*($date_diff_from_last_receive_to_due)/365;
                    $daily_interest_after_due = $principal_balance*$effective_interest_rate/100*($date_diff_from_due)/365;
                }else{// partial payment after due
                    $date_diff_from_last_receive_to_due = $last_receive_date->copy()->diffInDays($due_ymd);
                    $daily_interest_before_due = 0;
                    $daily_interest_after_due = $principal_balance*$effective_interest_rate/100*($date_diff_from_last_receive)/365;
                }
                //partial payment after due  
                $daily_interest_cal = floatval($daily_interest_before_due) + floatval($daily_interest_after_due);
                $daily_interest_cal = floor($daily_interest_cal*100)/100;
            }
            
        }else{
            $effective_interest_rate = $interest_rate - $discount_rate;
            $daily_interest_before_due = $principal_balance*$effective_interest_rate/100*($date_diff_from_last_receive)/365;
            $daily_interest_after_due = 0;
            $daily_interest_cal = floatval($daily_interest_before_due) + floatval($daily_interest_after_due);
            $daily_interest_cal = floor($daily_interest_cal*100)/100;
        }
        $principal = floor($this->order->purchase_amount*100)/100;
        $accru_paid_principal = floor($details->sum('paid_principal')*100)/100;
        $accru_paid_interest = floor($details->sum('paid_interest')*100)/100;
        $accru_paid_delay_penalty = floor($details->sum('paid_late_charge')*100)/100;
        $accru_total_paid =  $accru_paid_principal + $accru_paid_interest +$accru_paid_delay_penalty;
        $accru_interest = floor($details->sum('interest')*100)/100 + floor($daily_interest_cal*100)/100;
        $accru_delay_penalty = floor($details?->sum('late_charge')*100)/100 + floor($delay_penalty_cal*100)/100;
        $billing_interest = $accru_interest - $accru_paid_interest;
        $billing_delay_penalty = $accru_delay_penalty - $accru_paid_delay_penalty;
        $billing_principal = $principal - $accru_paid_principal;
        $sum_billing = $billing_principal + $billing_interest + $billing_delay_penalty;
        return [
            'last_receive_date'=>$last_receive_date,
            'sum_billing_interest'=>floor($details->sum('interest')*100)/100,
            'is_delay'=>$is_delay,
            'daily_interest'=>floor($daily_interest_cal*100)/100,
            'daily_interest_before_due'=>floor($daily_interest_before_due*100)/100,
            'daily_interest_after_due'=>floor($daily_interest_after_due*100)/100,
            'principal'=>$this->principal,
            'paid_principal'=>$this->paid_principal,
            'principal_balance'=>$principal_balance,
            'cal_date'=>$cal_date,
            'sum_delay_penalty'=>floor($details->sum('late_charge')*100)/100,
            'delay_penalty'=>floor($delay_penalty_cal*100)/100,
            'accru_interest'=>floor($accru_interest*100)/100,
            'accru_delay_penalty'=>floor($accru_delay_penalty*100)/100,
            'accru_paid_principal'=>$accru_paid_principal,
            'accru_paid_interest'=>$accru_paid_interest,
            'accru_paid_delay_penalty'=>$accru_paid_delay_penalty,
            'accru_total_paid'=>floatval($accru_total_paid),
            'billing_principal'=>floor($billing_principal*100)/100,
            'billing_interest'=>floor($billing_interest*100)/100,
            'billing_delay_penalty'=>floor($billing_delay_penalty*100)/100,
            'sum_billing'=>floor($sum_billing*100)/100,
            'effective_interest_rate'=>$effective_interest_rate,
            'date_diff_from_due'=>$date_diff_from_due,
            'date_diff_form_last_receive'=>$date_diff_from_last_receive,
            'date_diff_form_last_receive_to_due'=>$date_diff_from_last_receive_to_due ?? 'N/A',
        ];
    }

    /* public function delayPenalty(){
        if(!is_null($this->paid_up_ymd)) //already paid up
            return ["outstanding"=> 0, "last_due"=> null, "this_due"=>$this->due_ymd,
                    "installment_number"=>$this->installment_number,
                    "date_diff_from_last_due"=> null,
                    "delay_penalty" => 0,
                    "outstanding_interest"=>0,
                ];
        else if($this->due_ymd > Carbon::now()->isoFormat('YYYYMMDD')){//not delay yet
            $outstanding_interest = $this->interest - $this->paid_interest;
            return ["outstanding"=> 0, "last_due"=> null, "this_due"=>$this->due_ymd,
                "installment_number"=>$this->installment_number,
                "date_diff_from_last_due"=> null,
                "delay_penalty" => 0,
                "outstanding_interest"=>$outstanding_interest,
            ];
        } 
        else {
            $delay_penalty_rate = $this->delayPenaltyRate();
            $now = Carbon::now()->endOfDay();
            $partial_paid = $this->paid_principal + $this->paid_interest;
            $outstanding = $this->principal - $this->paid_principal + $this->interest - $this->paid_interest;
            $outstanding_interest = $this->interest - $this->paid_interest;
            if($this->installment_number > 1 ){ 
                if($partial_paid == 0){//no partial paid
                    $day = Carbon::parse($this->due_ymd)->format('d');
                    $month = Carbon::parse($this->due_ymd)->format('m');
                    $year = Carbon::parse($this->due_ymd)->format('Y');
                    $dt = Carbon::create($year,$month,$day,0)->settings([
                        'monthOverflow' => false,
                    ]);
                    if($day>15){               
                        $last_due = $dt->copy()->subMonth()->endOfMonth();//new Carbon('last day of last month');
                    }else{
                        $last_due = $dt->copy()->subMonth();//new Carbon('first day of last month');
                    }
                        
                    $date_diff = $now->diffInDays($last_due);
                    $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff+1)/365;
                    $delay_penalty_cal = $delay_penalty_cal - $this->paid_late_charge;
                }else{//has partial paid
                    $last_due = Carbon::parse($this->latestInstallmentHistory->from_ymd); //not yet paid delay penalty
                    $date_diff = $now->diffInDays($last_due);
                    $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff)/365;
                }
                
                //$date_diff_php = date_diff(date_create($now->copy()->format('Y-m-d')),date_create($last_due->copy()->format('Y-m-d')))->format('%a');
            }else{            
                if($partial_paid == 0){ //first installment no partial payment
                    $last_due = Carbon::parse($this->order->input_ymd); //to use input ymd instead
                    $date_diff = $now->diffInDays($last_due);
                    $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff+1)/365;
                    $delay_penalty_cal = $delay_penalty_cal - $this->paid_late_charge;
                }else{ //first installment has partial payment
                    $last_due = Carbon::parse($this->latestInstallmentHistory->from_ymd); //not yet paid delay penalty
                    $date_diff = $now->diffInDays($last_due);
                    $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff)/365;
                }
                
            }
            //without partial paid delay penalty
           
            //calculate delay penalty for each installment
            //return "Last Due: $last_due This Due = $this->due_ymd, Installment_number = $this->installment_number, date from last due: $date_diff";
            
            $rounded_delay_penalty_cal = floor($delay_penalty_cal*100)/100;
            return ["outstanding"=> $outstanding, "last_due"=> $last_due->format('Ymd'), "this_due"=>$this->due_ymd,
            "installment_number"=>$this->installment_number,
            "date_diff_from_last_due"=> $date_diff,
            "delay_penalty" => $rounded_delay_penalty_cal,
            "outstanding_interest"=>$outstanding_interest,];
        }
            
    } */

    //for Supply Chain Finance
    public function delayPenalty(){
        $getOrderID = request()->query('order_id');
        if(!is_null($this->paid_up_ymd)) //already paid up
            return ["outstanding"=> 0, "last_due"=> null, "this_due"=>$this->due_ymd,
                    "installment_number"=>$this->installment_number,
                    "date_diff_from_last_due"=> null,
                    "delay_penalty" => 0,
                    "outstanding_interest"=>0,
                    "daily_interest"=>0,
                ];
        else {
            // dd($this->order);
            $delay_penalty_rate = $this->delayPenaltyRate();
            $interest_rate = $this->interestRate();
            $discount_rate = $this->discountRate();
            $now = Carbon::now()->endOfDay();
            $partial_paid = $this->paid_principal + $this->paid_interest;
            $terms = ($this->interest*365)/($this->principal*$interest_rate/100);
            // dd(round($terms));
            // $interest = $this->principal*$interest_rate/100*($date_diff_from_due)/365;
            $discount_int = $this->principal*$discount_rate/100*$terms/365;
            // $interest_with_discount = $this->interest * (100 - $discount_rate) / 100;
            $interest_with_discount = $this->interest - $discount_int;

            // dd($this);
            // dd($interest_with_discount);

            // dd($partial_paid); // 3587.6711
            // dd($interest_with_discount); // 3587.6711
            // dd($this); // 3698.63

            // $last_due = Carbon::parse($this->order->input_ymd); //not yet paid delay penalty

            // $lastReceiveAmount = $this->ReceiveAmountDetail()->where('order_id', $getOrderID)->get()->last();
            // if($lastReceiveAmount!=null){
            //     $lastReceiveHis = $lastReceiveAmount->receive_history()->get()->last();
            //     $last_due =  Carbon::parse($lastReceiveHis->receive_ymd);
            //     // $now = $last_due;
            // }else{
            //     $last_due = Carbon::parse($this->order->input_ymd);
            // }
            // dd($this->due_ymd);

            // dpd
            if($partial_paid == 0){ // no partial paid
                $last_due =  Carbon::parse($this->due_ymd);
            } else { // has partial paid
                $lastReceiveAmount = $this->receive_amount_details()->where('order_id', $getOrderID)->get()->last();
                $lastReceiveHis = $lastReceiveAmount?->receive_history()->get()->last();
                $last_due =  Carbon::parse($lastReceiveHis?->receive_ymd);
            }

            if($now <= $last_due){
                $now = $last_due;
            }
            
            $old_last_due =  Carbon::parse($this->due_ymd);

            if($last_due->copy()->isoFormat('YYYYMMDD') > $old_last_due->copy()->isoFormat('YYYYMMDD')){
                // dd('old over due');
                $old_outstanding = $this->principal + $this->interest;
                $old_date_diff_from_due = $last_due->diffInDays($old_last_due);

                $old_delay_penalty_cal = $old_outstanding*$delay_penalty_rate/100*($old_date_diff_from_due)/365;
                $old_delay_penalty_cal = floor($old_delay_penalty_cal*100)/100;
            }else{
                // dd('old not over due');
                $old_delay_penalty_cal = 0;
            }

            if($now->copy()->isoFormat('YYYYMMDD') > $last_due->copy()->isoFormat('YYYYMMDD')){
                // dd('over due');
                $outstanding = $this->principal - $this->paid_principal + $this->interest - $this->paid_interest;
                $outstanding_interest = $this->interest - $this->paid_interest;
                $date_diff_from_due = $now->diffInDays($last_due);
                
                $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff_from_due)/365;
                $delay_penalty_cal = floor($delay_penalty_cal*100)/100;
                $delay_penalty_cal = $delay_penalty_cal - $this->paid_late_charge;

            } else {
                // dd('not over due');
                $outstanding = $this->principal - $this->paid_principal + $interest_with_discount - $this->paid_interest;
                $outstanding_interest = $interest_with_discount - $this->paid_interest;

                $date_diff_from_due = 0;
                $delay_penalty_cal = 0;
            }

            $delay_penalty_cal_total = $delay_penalty_cal + $old_delay_penalty_cal;

            $rounded_delay_penalty_cal = floor($delay_penalty_cal_total*100)/100;

            return [
                "outstanding"=> $outstanding,
                "last_due"=> $last_due->format('Ymd'),
                "this_due"=>$this->due_ymd,
                "installment_number"=>$this->installment_number,
                "date_diff_from_last_due"=> $date_diff_from_due,
                "delay_penalty" => $rounded_delay_penalty_cal,
                "outstanding_interest"=>$outstanding_interest,
                "daily_interest"=>$interest_with_discount,
                "date_diff_from_due"=>$date_diff_from_due,
            ];
        }    
    }

    //for Supply Chain Finance
    //dateformat yyyy-mm-dd 
    public function delayPenaltyWithDate($date){
        // return $date;
        // return $this;

        // $getOrderID = request()->query('order_id');
        $cal_date = Carbon::createFromFormat('Y-m-d',$date);
        if(!is_null($this->paid_up_ymd)) //already paid up
            return ["outstanding"=> 0, "last_due"=> null, "this_due"=>$this->due_ymd,
                    "installment_number"=>$this->installment_number,
                    "date_diff_from_last_due"=> null,
                    "delay_penalty" => 0,
                    "outstanding_interest"=>0,
                    "daily_interest"=>0,
                ];
        else {            
            $delay_penalty_rate = $this->delayPenaltyRate();
            $interest_rate = $this->interestRate();
            $discount_rate = $this->discountRate();
            // $now = Carbon::now()->endOfDay();
            $now = $cal_date;
            // return $this;
            $partial_paid = $this->paid_principal + $this->paid_interest;            
            $terms = ($this->interest*365)/($this->principal*$interest_rate/100);
            // dd(round($terms));
            // $interest = $this->principal*$interest_rate/100*($date_diff_from_due)/365;
            $discount_int = $this->principal*$discount_rate/100*$terms/365;
            // $interest_with_discount = $this->interest * (100 - $discount_rate) / 100;
            $interest_with_discount = $this->interest - $discount_int;
            // return $interest_with_discount;

            // dpd
            if($partial_paid == 0){ // no partial paid
                $last_due =  Carbon::parse($this->due_ymd);
            } else { // has partial paid
                $lastReceiveAmount = $this->ReceiveAmountDetail()->where('order_id', $this->order_id)->get()->last();
                $lastReceiveHis = $lastReceiveAmount->receive_history()->get()->last();
                $last_due =  Carbon::parse($lastReceiveHis->receive_ymd);
            }

            if($now <= $last_due){
                $now = $last_due;
            }
            
            $old_last_due =  Carbon::parse($this->due_ymd);

            if($last_due->copy()->isoFormat('YYYYMMDD') > $old_last_due->copy()->isoFormat('YYYYMMDD')){
                // dd('old over due');
                $old_outstanding = $this->principal + $this->interest;
                $old_date_diff_from_due = $last_due->diffInDays($old_last_due);

                $old_delay_penalty_cal = $old_outstanding*$delay_penalty_rate/100*($old_date_diff_from_due)/365;
                $old_delay_penalty_cal = floor($old_delay_penalty_cal*100)/100;
            }else{
                // dd('old not over due');
                $old_delay_penalty_cal = 0;
            }

            if($now->copy()->isoFormat('YYYYMMDD') > $last_due->copy()->isoFormat('YYYYMMDD')){
                // dd('over due');
                $outstanding = $this->principal - $this->paid_principal + $this->interest - $this->paid_interest;
                // $outstanding_interest = $this->interest - $this->paid_interest;
                $outstanding_interest = $this->interest;
                $date_diff_from_due = $now->diffInDays($last_due);
                
                $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff_from_due)/365;
                $delay_penalty_cal = floor($delay_penalty_cal*100)/100;
                // $delay_penalty_cal = $delay_penalty_cal - $this->paid_late_charge;

            } else {
                // dd('not over due');
                $outstanding = $this->principal - $this->paid_principal + $interest_with_discount - $this->paid_interest;
                // $outstanding_interest = $interest_with_discount - $this->paid_interest;
                $outstanding_interest = $interest_with_discount;

                $date_diff_from_due = 0;
                $delay_penalty_cal = 0;
            }

            $delay_penalty_cal_total = ($delay_penalty_cal + $old_delay_penalty_cal);

            $rounded_delay_penalty_cal = floor($delay_penalty_cal_total*100)/100;

            return [
                "outstanding"=> $outstanding,
                "last_due"=> $last_due->format('Ymd'),
                "this_due"=>$this->due_ymd,
                "installment_number"=>$this->installment_number,
                "date_diff_from_last_due"=> $date_diff_from_due,
                "delay_penalty" =>  $delay_penalty_cal_total,
                "outstanding_interest"=>$outstanding_interest,
                "daily_interest"=>$interest_with_discount,
                "date_diff_from_due"=>$date_diff_from_due,
            ];
        }
            
    }

    //for Bizloan
    //dateformat yyyy-mm-dd 
    public function interestWithDate($date){
        $cal_date = Carbon::createFromFormat('Y-m-d',$date);
        if(!is_null($this->paid_up_ymd)) //already paid up
            return [
                // "outstanding"=> 0,
                "transfer_date"=> null,
                "date_diff_from_transfer_date"=> null,
                "interest" => 0,
                // "outstanding_interest"=>0,
                // "daily_interest"=>0,
            ];
        else {
            $interest_rate = $this->interestRate();
            //return $interest_rate;
            $discount_rate = $this->discountRate();
            $delay_penalty_rate = $this->delayPenaltyRate();
            if(Carbon::createFromFormat('Y-m-d',$date)->isoFormat('YYYYMMDD') > $this->due_ymd){
                $is_delay = true;
                $effective_interest_rate = $interest_rate;
                $date_diff_from_transfer_date = Carbon::parse($this->order->purchase_ymd)->diffInDays($cal_date);
            }else{
                $is_delay = false;
                $effective_interest_rate = $interest_rate - $discount_rate;
                $date_diff_from_transfer_date = Carbon::parse($this->order->purchase_ymd)->diffInDays($cal_date);
            }
            $now = $cal_date;
            $partial_paid = $this->paid_principal + $this->paid_interest;
            $outstanding = $this->principal - $this->paid_principal;
            $outstanding = floor($outstanding*100)/100;
            
            // if($this->order->purchase_ymd >= Carbon::now()->isoFormat('YYYYMMDD'))
            //     $date_diff_from_transfer_date = 0;
            // else
            
           
            if($partial_paid == 0){ //first installment no partial payment
                //$transfer_ymd = Carbon::parse($this->order->purchase_ymd); //to use input ymd instead
                //$date_diff_from_transfer_date = $now->diffInDays($transfer_ymd);
                $daily_interest_cal = $outstanding*$effective_interest_rate/100*($date_diff_from_transfer_date)/365;
                $daily_interest_cal = floor($daily_interest_cal*100)/100;
                $due_ymd = Carbon::parse($this->due_ymd); 
                $date_diff_from_due = $now->diffInDays($due_ymd);
                if($this->due_ymd < $cal_date->copy()->isoFormat('YYYYMMDD')){
                    $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff_from_due)/365;
                    $delay_penalty_cal = floor($delay_penalty_cal*100)/100;
                    $delay_penalty_cal = $delay_penalty_cal - $this->paid_late_charge;
                }else{ //not delay yet
                    $delay_penalty_cal = 0;
                }
            }else{ //first installment has partial payment
                //$transfer_ymd = Carbon::parse($this->order->purchase_ymd); //not yet paid delay penalty
                //$date_diff_from_transfer_date = $now->diffInDays($transfer_ymd);
                $daily_interest_cal = $outstanding*$effective_interest_rate/100*($date_diff_from_transfer_date)/365;
                $daily_interest_cal = floor($daily_interest_cal*100)/100;
                $due_ymd = Carbon::parse($this->due_ymd); 
                $date_diff_from_due = $now->diffInDays($due_ymd);
                if($this->due_ymd < $cal_date->copy()->isoFormat('YYYYMMDD')){
                    $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff_from_due)/365;
                }else{
                    $delay_penalty_cal = 0;
                }
            }
            
            //without partial paid delay penalty
            
            //calculate delay penalty for each installment
            //return "Last Due: $last_due This Due = $this->due_ymd, Installment_number = $this->installment_number, date from last due: $date_diff";
            
            $rounded_delay_penalty_cal = floor($delay_penalty_cal*100)/100;
            $outstanding_interest = $daily_interest_cal - $this->paid_interest;
            $outstanding_delay_penalty = floor(($rounded_delay_penalty_cal - $this->paid_late_charge)*100)/100;
            return [
                "cal_date"=>$date,
                "outstanding"=> $outstanding,
                "transfer_ymd"=> $this->order->purchase_ymd,
                "due_ymd"=>$this->due_ymd,
                "installment_number"=>$this->installment_number,
                "delay_penalty" => $rounded_delay_penalty_cal,
                "outstanding_interest"=>$outstanding_interest,
                "outstanding_delay_penalty"=>$outstanding_delay_penalty,
                "daily_interest"=>$daily_interest_cal,
                "date_diff_from_due"=>$date_diff_from_due,
                "date_diff_from_transfer_date"=>$date_diff_from_transfer_date,
                "is_delay"=>$is_delay,
                "effective_interest_rate"=>$effective_interest_rate,
                "total_outstanding"=>floor(($outstanding+$daily_interest_cal+$rounded_delay_penalty_cal)*100)/100,
            ];
        }
            
    }

    public function installment_histories(){
        return $this->hasMany(InstallmentHistory::class,'installment_id');
    }

    public function latestInstallmentHistory(){
        return $this->hasOne(InstallmentHistory::class,'installment_id')->latestOfMany();
    }

    public function allocateReceiveAmount($date,$receive_amount){
        $billing_part = $this->calAccruInterestAndDelayPenalty($date);
        $delay_penalty = $billing_part['billing_delay_penalty'];
        $interest = $billing_part['billing_interest'];
        $outstanding_principal = $billing_part['billing_principal'];
        //return ['billing_delay_penalty'=>$delay_penalty,'billing_interest'=>$interest,'billing_principal'=>$outstanding_principal];
        $total_billing = $billing_part['sum_billing'];
        if($receive_amount < $delay_penalty){
            $allocate_delay_penalty = $receive_amount;
            $allocate_interest = 0;
            $allocate_principal = 0;
        }
        if($receive_amount >= $delay_penalty && ($receive_amount < $delay_penalty + $interest)){
            $allocate_delay_penalty = $delay_penalty;
            $allocate_interest = $receive_amount - $delay_penalty;
            $allocate_principal = 0;
        }
        if($receive_amount >= $delay_penalty + $interest){
            $allocate_delay_penalty = $delay_penalty;
            $allocate_interest = $interest;
            $allocate_principal = floatval($receive_amount) - floatval($delay_penalty) - floatval($interest);
        }
            
        $a = Arr::add($billing_part,'receive_amount',$receive_amount);
        $b = Arr::add($a,'allocate_delay_penalty',$allocate_delay_penalty);
        $c = Arr::add($b,'allocate_interest',$allocate_interest);
        $d = Arr::add($c,'allocate_principal',floatval($allocate_principal));
        $principal_balance = floor(($outstanding_principal-$allocate_principal)*100)/100;
        $interest_balance = floor(($interest-$allocate_interest)*100)/100;
        $delay_penalty_balance = floor(($delay_penalty-$allocate_delay_penalty)*100)/100;
        $e = Arr::add($d,'balance_principal',$principal_balance);
        $f = Arr::add($e,'balance_interest',$interest_balance);
        $g = Arr::add($f,'balance_delay_penalty',$delay_penalty_balance);
        $total_balance = floor(($principal_balance+$interest_balance+$delay_penalty_balance)*100)/100;
        return $h = Arr::add($g,'balance_total',$total_balance);

        return $outstanding_part;

    }
}
