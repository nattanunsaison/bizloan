<?php
namespace App\Enum;


enum ContractorStatus: int
{
    case Active = 1;
    case Inactive = 2;

    public function labels(): string
    {
        return match ($this) {
            self::Active         => __('words.Active'),
            self::Inactive       => __('words.Inactive'),
        };
    }

    public function colors(): string
    {
        return match ($this) {
            self::Active         => '#FB1005', //red
            self::Inactive       => '#474547', //gray
        };
    }

}