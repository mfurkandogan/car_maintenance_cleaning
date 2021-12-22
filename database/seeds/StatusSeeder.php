<?php

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
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
                'status_name' => 'Awaiting Payment',
                'status_code' => 'AP'
            ],
            [
                'status_name' => 'In Progress',
                'status_code' => 'IP'
            ],
            [
                'status_name' => 'Completed',
                'status_code' => 'C'
            ],
        );
        foreach ($data as $datum) {
            $status = new Status();
            $status->status_name = $datum['status_name'];
            $status->status_code = $datum['status_code'];
            $status->save();
        }
    }
}
