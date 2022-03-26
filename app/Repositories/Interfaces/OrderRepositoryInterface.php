<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;

interface OrderRepositoryInterface
{
    public function all();
    public function process(Order $order);
    public function save();
}
