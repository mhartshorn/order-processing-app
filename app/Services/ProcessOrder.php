<?php

namespace App\Services;

class ProcessOrder
{
    /**
     * Sum total order items not include shipping.
     *
     * @return float
     */
    private function sumTotalOrderValue($items)
    {
        $orderValue = 0.0;
        foreach ($items as $item) {
            $orderValue += $item->quantity * $item->unit_price;
        }
        return $orderValue;
    }

    /**
     * Apply all discounts.
     *
     * @return float
     */
    private function applyDiscounts($discounts, $totalValue)
    {
    //? We dont want to override $totalValue so we can use it to
    $discountedValue = $totalValue;

        foreach ($discounts as $discount) {
            switch ($discount->type) {
                case 'DOLLAR':
                    $discountedValue -= $discount->value;
                    break;
                case 'PERCENTAGE':
                    $percentageValue = ($discount->value / 100) * $totalValue;
                    $discountedValue -= $percentageValue;
                    break;
            }
        }
        return round($discountedValue, 2);
    }

    /**
     * Calculate average price of each unit in the order.
     *
     * @return float
     */
    private function calculateAvgOrderPrice($items)
    {
        $avgValue = 0.0;
        $totalUnitPrice = 0.0;

        foreach ($items as $item) {
            $totalUnitPrice += $item->unit_price;
        }

        $avgValue = $totalUnitPrice / count($items);
        return round($avgValue, 2);
    }

    /**
     * Get all units.
     *
     * @return array
     */
    private function getUnits($items)
    {
        $units = [];
        foreach ($items as $item) {
            $productId = $item->product->product_id;
            array_push($units, $productId);
        }
        return $units;
    }
}
