<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class installments extends Model
{
    use HasFactory;

    public function order(){
        return $this->belongsTo(order::class);
    }

    public function contractor(){
        return $this->belongsTo(contractors::class);
    }

    public function delayPenaltyRate(){
        return $this->order->contractor->delay_penalty_rate;
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
                    $delay_penalty_cal = floor($delay_penalty_cal*100)/100;
                    $delay_penalty_cal = $delay_penalty_cal - $this->paid_late_charge;
                }else{//has partial paid
                    $last_due = Carbon::parse($this->latestInstallmentHistory->from_ymd); //not yet paid delay penalty
                    $date_diff = $now->diffInDays($last_due);
                    $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff)/365;
                }
                
                //$date_diff_php = date_diff(date_create($now->copy()->format('Y-m-d')),date_create($last_due->copy()->format('Y-m-d')))->format('%a');
            }else{  
                //return "This loop";          
                if($partial_paid == 0){ //first installment no partial payment
                    //return "This loop 1";
                    $last_due = Carbon::parse($this->order->input_ymd); //to use input ymd instead
                    $date_diff = $now->diffInDays($last_due);
                    $daily_interest_cal = $outstanding*6/100*($date_diff)/365;
                    $daily_interest_cal = floor($daily_interest_cal*100)/100;
                    //check if today < due date or not
                    $due_ymd = Carbon::parse($this->due_ymd);
                    $date_diff_from_due = $now->diffInDays($due_ymd);
                    if($now->copy()->isoFormat('YYYYMMDD') > $this->due_ymd){
                        $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff_from_due)/365;
                        $delay_penalty_cal = floor($delay_penalty_cal*100)/100;
                        $delay_penalty_cal = $delay_penalty_cal - $this->paid_late_charge;
                    }else{
                        $date_diff_from_due = 0;
                        $delay_penalty_cal = 0;
                    }
                }else{ //first installment has partial payment
                    //return "This loop";
                    $last_due = Carbon::parse($this->order->input_ymd); //not yet paid delay penalty
                    $date_diff = $now->diffInDays($last_due);
                    $daily_interest_cal = $outstanding*6/100*($date_diff)/365;
                    $daily_interest_cal = floor($daily_interest_cal*100)/100;
                    $due_ymd = Carbon::parse($this->due_ymd);
                    $date_diff_from_due = $now->diffInDays($due_ymd);
                    $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff_from_due)/365;
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
            "outstanding_interest"=>$outstanding_interest,
            "daily_interest"=>$daily_interest_cal,
            "date_diff_from_due"=>$date_diff_from_due,
        ];
        }
            
    }

    //for Supply Chain Finance
    //dateformat yyyy-mm-dd 
    public function delayPenaltyWithDate($date){
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
            $now = $cal_date;
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
                    $delay_penalty_cal = floor($delay_penalty_cal*100)/100;
                    $delay_penalty_cal = $delay_penalty_cal - $this->paid_late_charge;
                }else{//has partial paid
                    $last_due = Carbon::parse($this->latestInstallmentHistory->from_ymd); //not yet paid delay penalty
                    $date_diff = $now->diffInDays($last_due);
                    $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff)/365;
                }
                
                //$date_diff_php = date_diff(date_create($now->copy()->format('Y-m-d')),date_create($last_due->copy()->format('Y-m-d')))->format('%a');
            }else{            
                if($partial_paid == 0){ //first installment no partial payment
                    $input_ymd = Carbon::parse($this->order->input_ymd); //to use input ymd instead
                    $date_diff_from_input = $now->diffInDays($input_ymd);
                    $daily_interest_cal = $outstanding*6/100*($date_diff_from_input)/365;
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
                    $input_ymd = Carbon::parse($this->order->input_ymd); //not yet paid delay penalty
                    $date_diff_from_input = $now->diffInDays($input_ymd);
                    $daily_interest_cal = $outstanding*6/100*($date_diff_from_input)/365;
                    $daily_interest_cal = floor($daily_interest_cal*100)/100;
                    $due_ymd = Carbon::parse($this->due_ymd); 
                    $date_diff_from_due = $now->diffInDays($due_ymd);
                    if($this->due_ymd < $cal_date->copy()->isoFormat('YYYYMMDD')){
                        $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff_from_due)/365;
                    }else
                        $delay_penalty_cal = 0;
                }
                
            }
            //without partial paid delay penalty
            
            //calculate delay penalty for each installment
            //return "Last Due: $last_due This Due = $this->due_ymd, Installment_number = $this->installment_number, date from last due: $date_diff";
            
            $rounded_delay_penalty_cal = floor($delay_penalty_cal*100)/100;
            return ["outstanding"=> $outstanding, "input_ymd"=> $input_ymd->format('Ymd'), "due_ymd"=>$this->due_ymd,
            "installment_number"=>$this->installment_number,
            "date_diff_from_input"=> $date_diff_from_input,
            "delay_penalty" => $rounded_delay_penalty_cal,
            "outstanding_interest"=>$outstanding_interest,
            "daily_interest"=>$daily_interest_cal,
            "date_diff_from_due"=>$date_diff_from_due,
        ];
        }
            
    }

    public function installment_histories(){
        return $this->hasMany(InstallmentHistory::class,'installment_id');
    }

    public function latestInstallmentHistory(){
        return $this->hasOne(InstallmentHistory::class,'installment_id')->latestOfMany();
    }
}
