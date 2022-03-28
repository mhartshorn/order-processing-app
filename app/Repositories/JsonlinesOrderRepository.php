<?php

namespace App\Repositories;

use App\Models\Order;
use App\Services\ProcessOrder;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class JsonlinesOrderRepository implements OrderRepositoryInterface
{

    /**
     * The filepath of the jsonlines file.
     *
     * @var string
     */
    public $path = 'https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1/orders.jsonl';

    /**
     * The output csv filename .
     *
     * @var string
     */
    public $output = 'out.csv';

    /**
     * Process a sigle order array.
     *
     * @return object
     */
    public function fetch()
    {
        $orders = [];
        $fileStream = fopen($this->path, "r");
        //? Loop while not end of file
        while(!feof($fileStream)){
            //? Push the current line to array
            $order = json_decode(fgets($fileStream));
            array_push($orders, $order);
        }
        fclose($fileStream);
        var_dump($orders);

        return $orders;
    }


    /**
     * Process a sigle order array.
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
}
