<?php
namespace App\Enum;


enum ApplicationType: int
{
    case Online = 1;
    case Paper = 2;

    public function labels(): string
    {
        return match ($this) {
            self::Online         => __('Online'),
            self::Paper       => __('Paper'),
        };
    }
}