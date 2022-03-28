<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;

interface OrderRepositoryInterface
{
    public function fetch();
    public function process($orderList);
    public function save($orders);
}
