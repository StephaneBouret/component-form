<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension 
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('amount', [$this, 'amount'])
        ];
    }

    public function amount($value, string $symbol = '€', string $descsep = ',', string $thousandsep = ' ')
    {
        $finalValue = $value / 100;
        $finalValue = number_format($finalValue, 2, $descsep, $thousandsep);

        return $finalValue . ' ' . $symbol;
    }
}