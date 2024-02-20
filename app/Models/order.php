<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class order extends Model
{

    protected $fillable =[
        'order_number',
        'customer_id',
        'order_type',
        'product_id',
        'product_offering_id',
        'bill_date',
        'installment_count',
        'purchase_ymd',
        'purchase_amount',
        'paid_pricipal',
        'paid_interest',
        'paid_late_charge'
    ];

    public function installments(){
        return $this->hasMany(installments::class,'order_id');
    }

    public function contractor(){
        return $this->belongsTo(contractors::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id');
    }

    public function dealer(){
        return $this->belongsTo(dealers::class);
    }

    public function product(){
        return $this->belongsTo(product::class);
    }

    public function product_offering(){
        return $this->belongsTo(ProductOffering::class);
    }

    public function scopeNotDelete($query){
        return $query->where('deleted',0);
    }

    public function receive_histories(){
        return $this->hasMany(ReceiveAmountHistory::class);
    }
}
