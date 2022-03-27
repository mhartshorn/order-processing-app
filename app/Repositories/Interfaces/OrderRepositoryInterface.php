<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;

interface OrderRepositoryInterface
{
    public function process(object $orderList);
    public function save(array $orders);
}
