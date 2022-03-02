<?php

namespace App\Contracts\Services;

use Carbon\Carbon;

interface ExportOrdersServiceInterface
{
    /**
     * @param Carbon $date
     * @return mixed
     * throws NoOrdersAvailableException if no orders are available
     */
    public function export(Carbon $date): string;
}
