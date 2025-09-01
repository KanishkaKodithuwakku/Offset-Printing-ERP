<?php

namespace App\Helpers;

class StatusHelper
{
    /**
     * Get the user-friendly status text.
     *
     * @param string $status
     * @return string
     */
    public static function getJobOrderStatus($status)
    {
        $statusMap = [
            'printing' => 'Exposing - CTP',
            'designing' => 'Designing - DTP',
            'pending' => 'Pending',
            'dispatching' => 'CTP / Dispatch',
            'invoicing' => 'Invoicing',
            'invoiced' => 'Invoiced',
            'ready-to-invoice' => 'Billing',
        ];

        return $statusMap[$status] ?? 'Unknown Status';
    }
}
