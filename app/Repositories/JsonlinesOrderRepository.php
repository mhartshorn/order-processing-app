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
     * Get all orders from json lines file extension.
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
            echo $order;
            // $this->process($this->jsonToArray($order));
        }
        fclose($fileStream);
    }

    /**
     * Process a sigle order.
     *
     * @return void
     */
    public function process(Order $order)
    {

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
     * Transform json to Array.
     *
     * @return Array
     */
    public function jsonToArray($json)
    {
        return json_decode($json, true);
    }

}
