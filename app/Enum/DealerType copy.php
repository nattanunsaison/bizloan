<?php
namespace App\Enum;


enum DealerType: int
{
    case CBM = 1;
    case CPAC = 2;
    case GlobalHouse = 3;
    case Qmix = 4;
    case Transformer = 5;
    case CpacSolution = 6;
    case B2B = 7;
    case Nam = 8;
    case BigTH = 9;
    case Permsin = 10;
    case Scgp = 11;
    case Rakmao = 12;
    case Cotto = 13;
    case Government = 14;

    public function labels(): string
    {
        return match ($this) {
            self::CBM         => __('CBM'),
            self::CPAC       => __('CPAC smile'),
            self::GlobalHouse         => __('Global house'),
            self::Qmix       => __('Qmix'),
            self::Transformer         => __('Suply chain finance'),
            self::CpacSolution       => __('CPAC solutions'),
            self::B2B         => __('B2B'),
            self::Nam       => __('NAM'),
            self::BigTH         => __('Big Thailand'),
            self::Permsin       => __('Permsin'),
            self::Scgp         => __('Boonthavorn'),
            self::Rakmao       => __('Rakmao'),
            self::Cotto       => __('Cotto'),
            self::Government => __('Government'),
        };
    }

    public function abbreviation(): string{
        return match ($this) {
            self::CBM           => 'CB',
            self::CPAC          => 'CP',
            self::GlobalHouse   => 'GH',
            self::Qmix          => 'QM',
            self::Transformer   => 'SCF',
            self::CpacSolution  => 'CPS',
            self::B2B           => 'B2B',
            self::Nam           => 'NAM',
            self::BigTH         => 'BIG',
            self::Permsin       => 'PS',
            self::Scgp          => 'BTV',
            self::Rakmao        => 'RK',
            self::Cotto         => 'COT',
            self::Government    => 'GOV',
        }; 
    }

    public function map_with_actual_summaries(): string{
        return match ($this) {
            self::CBM           => 'cbm',
            self::CPAC          => 'cpac',
            self::GlobalHouse   => 'global_house',
            self::Qmix          => 'q_mix',
            self::Transformer   => 'transformer',
            self::CpacSolution  => 'cpac_sol',
            self::B2B           => 'b2b',
            self::Nam           => 'nam',
            self::BigTH         => 'bigth',
            self::Permsin       => 'permsin',
            self::Scgp          => 'scgp',
            self::Rakmao        => 'rakmao',
            self::Cotto         => 'cotto',
            self::Government    => 'd_gov',
        }; 
    }

    public function colors(): string{
        return match ($this) {
            self::CBM           => '#ff3f33',
            self::CPAC          => '#ff8033',
            self::GlobalHouse   => '#ffc433',
            self::Qmix          => '#fff333',
            self::Transformer   => '#bbff33',
            self::CpacSolution  => '#5bff33',
            self::B2B           => '#33ff74',
            self::Nam           => '#33ffda',
            self::BigTH         => '#33caff',
            self::Permsin       => '#3355ff',
            self::Scgp          => '#7133ff',
            self::Rakmao        => '#e633ff',
            self::Cotto         => '#ff33c4',
            self::Government    => '#ff3368',
        }; 
    }
}