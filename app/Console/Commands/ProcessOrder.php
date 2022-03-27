<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\JsonlinesOrderRepository;

class ProcessOrder extends Command
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
    public function handle(JsonlinesOrderRepository $repo)
    {
        $this->info("Opening file stream");

        $filepath = $repo->filepath;
        $fileStream = fopen($filepath, "r");
        $orders = [];
        //? Loop while not end of file
        while(!feof($fileStream)){
            //? Get the current line from file pointer
            $orderList = fgets($fileStream);
            $order = $repo->process(
                $repo->toArray($orderList)
            );
            $this->info("Processed order " . $order->id);
            array_push($orders, $order);
        }
        fclose($fileStream);
        $this->info("Exporting orders to csv");
        $repo->save($orders);
        return 0;
    }
}
