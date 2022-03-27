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
    protected $filepath = 'https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1/orders.jsonl';

    /**
     * Get all orders from json lines file.
     *
     * @return void
     */
    public function all()
    {
        $fileStream = fopen($this->filepath, "r");

        //? Loop while not end of file
        while(!feof($fileStream)){
            //? Get the current line from file pointer
            $order = fgets($fileStream);
            $this->process(
                $this->jsonToArray($order)
            );
        }
        fclose($fileStream);
    }

    /**
     * Save the order to csv file.
     *
     * @return void
     */
    public function save()
    {

    }

    /**
     * Process a sigle order array.
     *
     * @return void
     */
    public function process(array $orders)
    {
        // foreach ($orders['discounts'] as $discount) {
        //     print_r($discount['value']);
        // }
        // var_dump($orders);
        $totalValue = $this->sumTotalOrderValue($orders['items']);
        if(!empty($orders['discounts'])){
            $totalValue = $this->applyDiscounts($orders['discounts'], $totalValue);
        }

        if ($totalValue) {
            $order = new Order();

            $order->setId($orders['order_id'])
                ->setDatetime($orders['order_date'])
                ->setTotalValue($totalValue);

            var_dump($order);
        }
    }

    /**
     * Sum total order items not include shipping.
     *
     * @return float
     */
    private function sumTotalOrderValue(array $items): float
    {
        $orderValue = 0.0;
        foreach ($items as $item) {
            $orderValue += $item['quantity'] * $item['unit_price'];
        }
        return $orderValue;
    }

    /**
     * Apply all discounts.
     *
     * @return float
     */
    private function applyDiscounts(
            array $discounts, float $totalValue): float
    {
        //? We dont want to override $totalValue for use in calculations
        $discountedValue = $totalValue;

        foreach ($discounts as $discount) {
            switch ($discount['type']) {
                case 'DOLLAR':
                    $discountedValue = $discountedValue - $discount['value'];
                    break;
                case 'PERCENTAGE':
                    $percentageValue = ($discount['value'] / 100) * $totalValue;
                    $discountedValue = $discountedValue - $percentageValue;
                    break;
            }
        }

        return round($discountedValue, 2);
    }

    /**
     * Transform json to array.
     *
     * @return Array
     */
    private function jsonToArray($json)
    {
        return json_decode($json, true);
    }

}
