<?php
namespace App\Enum;


enum FlagText: int
{
    case _0 = 0;
    case _1 = 1;
    
    public function status(): string
    {
        return match ($this) {
            self::_0  => 'Inactive',
            self::_1  => 'Active',
        };
    }
    
    public function master_agreement(): string
    {
        return match ($this) {
            self::_0  => 'Uncomplete',
            self::_1  => 'Completed',
        };
    }
    
    public function ready_status(): string
    {
        return match ($this) {
            self::_0  => 'Not ready',
            self::_1  => 'Ready',
        };
    }
}