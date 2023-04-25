<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScfReceiveAmountHistory extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'order_id',
        'receive_ymd',
        'receive_amount',
        'exemption_late_charge',
        'exemption_interest',
        'comment',
        'create_user_id',
        'net_pay_amount',
        'paid_up_ymd',
        'deleted_user_id',
        'delete_reasons'
    ];
    
    public function order(){
        return $this->belongsTo(order::class);
    }

    public function receive_amount_detail(){
        return $this->hasOne(ReceiveAmountDetail::class,'scf_receive_amount_history_id');
    }
}
