<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class order extends Model
{

    public function installments(){
        return $this->hasMany(installments::class,'order_id');
    }

    public function contractor(){
        return $this->belongsTo(contractors::class);
    }

    public function dealer(){
        return $this->belongsTo(dealers::class);
    }

    public function product(){
        return $this->belongsTo(products::class);
    }

    public function scopeNotDelete($query){
        return $query->where('deleted',0);
    }

    public function receive_records(){
        return $this->hasMany(ScfReceiveAmountHistory::class);
    }
}
