<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class contractors extends Model
{
    use HasFactory,Notifiable;

    public function contractorUsers(){
        return $this->hasMany(ContractorUser::class,'contractor_id');
    }

    public function orders(){
        return $this->hasMany(order::class,'contractor_id');
    }

    public function eligibilities(){
        return $this->hasMany(Eligibility::class,'contractor_id');
    }

    protected $casts = [
        'status' => \App\Enum\ContractorStatus::class,
        'application_type'=> \App\Enum\ApplicationType::class,
        'approval_status'=>\App\Enum\ApprovalStatus::class,
        'contractor_type'=>\App\Enum\ContractorType::class,
    ];
}
