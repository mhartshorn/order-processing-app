<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class JsonlinesOrderRepository implements OrderRepositoryInterface
{

    /**
     * The filepath of the jsonlines file.
     *
     * @var string
     */
    public $filepath = 'https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1/orders.jsonl';

    /**
     * The output csv filename.
     *
     * @var string
     */
    public $output = 'out.csv';

    /**
     * Process a sigle order.
     *
     * @return Order
     */
    public function process($orderList)
    {
        $totalValue = $this->sumTotalOrderValue($orderList->items);

        if(!empty($orderList->discounts)){
            $totalValue = $this->applyDiscounts($orderList->discounts, $totalValue);
        }

        $avgUnitPrice = $this->calculateAvgOrderPrice($orderList->items);
        $units = $this->getUnits($orderList->items);
        $distinctUnits = array_unique($units);

        $customerState = $orderList->customer->shipping_address->state;

        if ($totalValue) {
            $order = new Order();

            $order->setId($orderList->order_id)
                ->setDatetime($orderList->order_date)
                ->setTotalValue($totalValue)
                ->setAvgUnitPrice($avgUnitPrice)
                ->setDistinctUnits(count($distinctUnits))
                ->setTotalUnits(count($units))
                ->setCustomerState($customerState);

            return $order;
        }
    }

    /**
     * Save the order to csv file.
     *
     * @return void
     */
    public function save($orders)
    {
        $path = storage_path('orders/');
        $file = fopen($path . $this->output, 'w');

        $columns = [
            'order_id',
            'order_datetime',
            'total_order_value',
            'average_unit_price',
            'distinct_unit_count',
            'total_units_count',
            'customer_state',
        ];

        fputcsv($file, $columns);

        foreach ($orders as $order) {
            $data = [
                $order->id,
                $order->datetime,
                $order->totalValue,
                $order->avgUnitPrice,
                $order->distinctUnits,
                $order->totalUnits,
                $order->customerState,
            ];
            fputcsv($file, $data);
        }

        fclose($file);
    }

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
        //? We dont want to override $totalValue for use in calculations
        $discountedValue = $totalValue;

        foreach ($discounts as $discount) {
            if ($discount->type == 'PERCENTAGE') {
                $percentageValue = ($discount->value / 100) * $totalValue;
                $discountedValue -= $percentageValue;
            } else {
                $discountedValue -= $discount->value;
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

    /**
     * Transform json to array.
     *
     * @return Array
     */
    public function toArray($json)
    {
        return json_decode($json);
    }

}
