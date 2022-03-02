<?php

namespace App\Contracts\Repositories;

use Carbon\Carbon;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function getItem($id);

    public function loadByDate(Carbon $date);
}
