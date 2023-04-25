<?php
namespace App\Enum;


enum ContractorType: int
{
    case Contractor = 1;
    case Subdealer = 2;
    case IndividualSubdealer = 3;
    case Government = 4;

    public function labels(): string
    {
        return match ($this) {
            self::Contractor         => __('Contractor'),
            self::Subdealer       => __('Sub dealer'),
            self::IndividualSubdealer      => __('Individual sub dealer'),
            self::Government  => __('Government'),
        };
    }

    public function abbreviation(): string{
        return match ($this) {
            self::Contractor                => 'C',
            self::Subdealer             => 'S',
            self::IndividualSubdealer   => 'I',
            self::Government            => 'GOV',
        };        
    }

    public function colors(): string{
        return match ($this) {
            self::Contractor            => '#FF5733', //orange
            self::Subdealer             => '#36FF33', //green
            self::IndividualSubdealer   => '#335BFF', //blue
            self::Government            => '#FF33F0', //pink
        };        
    }
}