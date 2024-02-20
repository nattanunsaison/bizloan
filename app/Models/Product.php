<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function orders(){
        return $this->hasMany(order::class,'product_id');
    }

    public function productOffering(){
        return $this->hasMany(productOffering::class);
    }
}