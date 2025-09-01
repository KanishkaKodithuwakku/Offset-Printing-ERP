<?php

if (!function_exists('format_currency')) {
    function format_currency($dc, $amount)
    {
        return ($dc === 'D' ? 'Dr ' : 'Cr ') . number_format($amount, 2);
    }
}
