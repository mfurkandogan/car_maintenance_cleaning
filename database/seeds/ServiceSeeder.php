<?php

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            [
                'service_name' => 'maintenance',
                'service_price' => 100
            ],
            [
                'service_name' => 'cleaning',
                'service_price' => 50
            ],
            [
                'service_name' => 'painting',
                'service_price' => 75
            ],
        );
        foreach ($data as $datum) {
            $service = new Service();
            $service->service_name = $datum['service_name'];
            $service->service_price = $datum['service_price'];
            $service->save();
        }
    }
}
