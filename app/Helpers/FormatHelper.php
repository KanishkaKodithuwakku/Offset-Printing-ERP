<?php

namespace App\Helpers;

class FormatHelper
{
    public static function formatCurrency($dc, $amount)
    {
        $prefix = $dc === 'D' ? 'Dr ' : ($dc === 'C' ? 'Cr ' : '');
        return $prefix . number_format($amount, 2, '.', ',');
    }
}
