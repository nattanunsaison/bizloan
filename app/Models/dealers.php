<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dealers extends Model
{
    use HasFactory;

    public function dealer_type_setting(){
        return $this->hasOne(dealer_type_settings::class,'dealer_type');
    }

    protected $casts = [
        'dealer_type'=>\App\Enum\DealerType::class,
    ];

    public function scb_account(){
        return $this->hasOne(DealerBankAccountDetail::class,'tax_id','tax_id');
    }

    public function orders(){
        return $this->hasMany(order::class,'dealer_id');
    }
}
