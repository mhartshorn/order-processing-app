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
    public function handle(JsonlinesOrderRepository $jsonlRepo)
    {
        $jsonlRepo->all();
        return 0;
    }
}
