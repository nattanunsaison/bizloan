<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class installmentHistory extends Model
{
    use HasFactory;
    protected $fillable =[
        'customer_id',
        'order_id',
        'installment_id',
        'paid_principal',
        'paid_interest',
        'paid_late_charge',
    ];
    
}
