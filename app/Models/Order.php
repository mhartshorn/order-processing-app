<?php

namespace App\Models;

use Carbon\Carbon;

class Order
{
    public int $id;
    public int $totalUnits;
    public int $distinctUnits;

    public float $totalValue;
    public float $avgUnitPrice;

    public string $datetime;
    public string $customerState;

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
     * Set the totalValue.
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
    public function setAvgUnitPrice(float $value)
    {
        $this->avgUnitPrice = $value;
        return $this;
    }

    /**
     * Set total distinct order units.
     *
     * @return Order
     */
    public function setDistinctUnits(int $units)
    {
        $this->distinctUnits = $units;
        return $this;
    }

    /**
     * Set total order units.
     *
     * @return Order
     */
    public function setTotalUnits(int $units)
    {
        $this->totalUnits = $units;
        return $this;
    }

    /**
     * Set customer state.
     *
     * @return Order
     */
    public function setCustomerState(string $state)
    {
        $this->customerState = $state;
        return $this;
    }

}
