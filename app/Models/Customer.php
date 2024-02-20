<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable =[
        // 'con_id',
        'tax_id',
        'existing_customer',
        'th_company_name',
        'en_company_name',
        'customer_address',
        'customer_email',
        'customer_phone_number',
        'status',
        'business_loan_amount',
        'offering_grade',
        'kyc_pic',
        'master_agreement',
        'ready_status'
    ];
    
    public function orders(){
        return $this->hasMany(order::class,'customer_id');
    }
}
