<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProcessOrder;
use App\Repositories\JsonlinesOrderRepository;

class ProcessOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process orders and output to csv';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(JsonlinesOrderRepository $repo, ProcessOrder $process)
    {
        $this->info("Opening file stream");
        $orderList = $repo->fetch();

        $orders = [];
        foreach ($orderList as $orderItem) {
            $this->info("Processing order " . $orderItem->id);
            $totalValue = $process->sumTotalOrderValue($orderItem->items);

            if ($totalValue) {

                if(!empty($orderList->discounts)){
                    $totalValue = $process->applyDiscounts($orderItem->discounts, $totalValue);
                }

                $avgUnitPrice = $process->calculateAvgOrderPrice($orderItem->items);
                $units = $process->getUnits($orderItem->items);
                $distinctUnits = array_unique($units);

                $customerState = $process->customer->shipping_address->state;

                $order = new Order();

                $order->setId($orderList->order_id)
                    ->setDatetime($orderList->order_date)
                    ->setTotalValue($totalValue)
                    ->setAvgUnitPrice($avgUnitPrice)
                    ->setDistinctUnits(count($distinctUnits))
                    ->setTotalUnits(count($units))
                    ->setCustomerState($customerState);
                array_push($orders, $order);
            }
        }
        var_dump($orders);


        // $filepath = $repo->filepath;
        // $fileStream = fopen($filepath, "r");
        // $orders = [];
        // //? Loop while not end of file
        // while(!feof($fileStream)){
        //     //? Get the current line from file pointer
        //     $orderList = fgets($fileStream);
        //     $order = $repo->process(
        //         $repo->toArray($orderList)
        //     );

        //     array_push($orders, $order);
        // }
        // fclose($fileStream);
        // $this->info("Exporting orders to csv");
        // $repo->save($orders);
        return 0;
    }
}
