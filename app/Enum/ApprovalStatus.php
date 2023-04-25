<?php
namespace App\Enum;


enum ApprovalStatus: int
{
    case Preregistration = 1;
    case Processing = 2;
    case Qualified = 3;
    case Rejected = 4;
    case Draft = 5;

    public function labels(): string
    {
        return match ($this) {
            self::Preregistration         => __('Preregistration'),
            self::Processing       => __('Processing'),
            self::Qualified       => __('Qualified'),
            self::Rejected       => __('Rejected'),
            self::Draft       => __('Draft'),
        };
    }
}