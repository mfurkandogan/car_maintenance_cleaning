<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Mockery\Exception;

class GetCars extends Command
{
    const CAR_REDIS_KEY = 'cars';
    const CAR_DETAIL_LINK = 'https://static.novassets.com/automobile.json';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cars:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all cars';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::get(self::CAR_DETAIL_LINK);

        try {
            if (200 == $response->status()) {
                $responseBody = json_decode($response->getBody(), true);
                foreach ($responseBody["RECORDS"] as $record){
                    Redis::set(self::CAR_REDIS_KEY.'_'.$record["id"], json_encode($record));
                }

                return true;
            }
        } catch (\Exception $exception) {
            throw new Exception('Error loading json extension '.$exception->getMessage());
        }
        return false;
    }
}
