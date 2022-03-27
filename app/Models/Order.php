<?php

namespace App\Models;

use Carbon\Carbon;

class Order
{
    private int $id;
    private string $datetime;
    private float $totalValue;
    private float $avgUnitePrice;
    private int $distinctUnits;
    private int $totalUnits;

    /**
     * Set the order id.
     *
     * @return Order
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set the datetime using Carbon.
     *
     * @return Order
     */
    public function setDatetime(string $datetime)
    {
        $this->datetime = Carbon::createFromTimestampUTC($datetime)
                                ->format('Y-m-d\TH:i:sP');
        return $this;
    }

    /**
     * Set the totalValue .
     *
     * @return Order
     */
    public function setTotalValue(float $value)
    {
        $this->totalValue = $value;
        return $this;
    }

    /**
     * Set the average unit price.
     *
     * @return Order
     */
    public function setAvgUnitePrice(float $value)
    {
        $this->avgUnitePrice = $value;
        return $this;
    }

    /**
     * Set total distinct order units.
     *
     * @return Order
     */
    public function setDistinctUnits(array $orders)
    {
        $this->distinctUnits = $orderCost;
        return $this;
    }

    /**
     * Set total order units.
     *
     * @return Order
     */
    public function setTotalUnits(array $orders)
    {
        $this->totalUnits = $orders;
        return $this;
    }

}
