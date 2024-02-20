<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOffering extends Model
{
    use HasFactory;
    
    public function orders(){
        return $this->hasMany(order::class,'product_offering_id');
    }
    
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}