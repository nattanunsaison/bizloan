<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ReceiveAmountDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'receive_amount_history_id',
        'dealer_type',
        'installment_number',
        'switched_date',
        'rescheduled_date',
        'repayment_ymd',
        'principal',
        'interest',
        'late_charge',
        'paid_principal',
        'paid_interest',
        'paid_late_charge',
        'total_principal',
        'total_interest',
        'total_late_charge',
        'waive_late_charge',
        'waive_interest',
        'contractor_id',
        'payment_id',
        'order_id',
        'installment_id',
        'dealer_id',
        'exceeded_occurred_amount',
        'payback_amount',
        'outstanding_balance',
        'tax',
        'paid_tax'
    ];
    public function installment(){
        return $this->belongsTo(installments::class, 'installment_id', 'id');
    }

    public function receive_history(){
        return $this->belongsTo(ReceiveAmountHistory::class,'receive_amount_history_id', 'id');
    }

    //for Supply Chain Finance
    public function delayPenalty(){
        if(!is_null($this->scf_receive_history->paid_up_ymd)) //already paid up
            return ["outstanding"=> 0, "last_due"=> null, "this_due"=>$this->due_ymd,
                    "installment_number"=>null,
                    "date_diff_from_last_due"=> null,
                    "delay_penalty" => 0,
                    "outstanding_interest"=>0,
                    "daily_interest"=>0,
                ];
        else {
            $delay_penalty_rate = 18;//$this->delayPenaltyRate();
            $now = Carbon::now()->endOfDay();
            $outstanding = $this->outstanding_balance;
            $latest_paid_date = $this->repayment_ymd;

            $date_diff = $now->diffInDays(Carbon::parse($latest_paid_date));
             
            $daily_interest_cal = $outstanding_interest = $outstanding*6/100*($date_diff)/365;

            $daily_interest_cal = $outstanding_interest = floor($daily_interest_cal*100)/100;

            $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff)/365;
                

            //without partial paid delay penalty
            
            //calculate delay penalty for each installment
            //return "Last Due: $last_due This Due = $this->due_ymd, Installment_number = $this->installment_number, date from last due: $date_diff";
            
            $rounded_delay_penalty_cal = floor($delay_penalty_cal*100)/100;
            return ["outstanding"=> $outstanding, "last_due"=> $latest_paid_date, "this_due"=>$this->due_ymd,
                "installment_number"=>$this->installment_number,
                "date_diff_from_last_due"=> $date_diff,
                "delay_penalty" => $rounded_delay_penalty_cal,
                "outstanding_interest"=>$outstanding_interest,
                "daily_interest"=>$daily_interest_cal,
                "date_diff_from_due"=>null,
        ];
        }
            
    }

    //for Supply Chain Finance
    public function delayPenaltyWithDate($date){
        if(!is_null($this->scf_receive_history->paid_up_ymd)) //already paid up
            return ["outstanding"=> 0, "last_due"=> null, "this_due"=>$this->due_ymd,
                    "installment_number"=>null,
                    "date_diff_from_last_due"=> null,
                    "delay_penalty" => 0,
                    "outstanding_interest"=>0,
                    "daily_interest"=>0,
                ];
        else {
            $delay_penalty_rate = 18;//$this->delayPenaltyRate();
            $now = Carbon::parse($date)->endOfDay();
            $outstanding = $this->outstanding_balance;
            $latest_paid_date = $this->repayment_ymd;

            $date_diff = $now->diffInDays(Carbon::parse($latest_paid_date));
             
            $daily_interest_cal = $outstanding_interest = $outstanding*6/100*($date_diff)/365;

            $daily_interest_cal = $outstanding_interest = floor($daily_interest_cal*100)/100;

            $delay_penalty_cal = $outstanding*$delay_penalty_rate/100*($date_diff)/365;
                

            //without partial paid delay penalty
            
            //calculate delay penalty for each installment
            //return "Last Due: $last_due This Due = $this->due_ymd, Installment_number = $this->installment_number, date from last due: $date_diff";
            
            $rounded_delay_penalty_cal = floor($delay_penalty_cal*100)/100;
            return ["outstanding"=> $outstanding, "last_due"=> $latest_paid_date, "this_due"=>$this->due_ymd,
                "installment_number"=>$this->installment_number,
                "date_diff_from_last_due"=> $date_diff,
                "delay_penalty" => $rounded_delay_penalty_cal,
                "outstanding_interest"=>$outstanding_interest,
                "daily_interest"=>$daily_interest_cal,
                "date_diff_from_due"=>null,
        ];
        }
            
    }
}
