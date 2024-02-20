<?php
namespace App\Enum;


enum BankCode: int
{
    case BBL = 002;
    case KBANK = 004;
    case KTB = 006;
    case TTB = 011;
    case SCB = 014;
    case CITI = 017;
    case CIMBT = 020;
    case BAY = 025;

    public function bankName(): string
    {
        return match ($this) {
            self::BBL         => __('Bangkok Bank Public Company Ltd.'),
            self::KBANK       => __('Kasikornbank Public Company Ltd.'),
            self::KTB         => __('Krung Thai Bank Public Company Ltd.'),
            self::TTB       => __('TMBThanachart Bank Public Company Ltd.'),
            self::SCB       => __('Siam Commercial Bank Public Company Ltd.'),
            self::CITI       => __('Citibank, N.A.'),
            self::CIMBT       => __('CIMB Thai Bank Public Company Limited'),
            self::BAY       => __('Bank Of Ayudhya Public Company Ltd.'),
        };
    }
}