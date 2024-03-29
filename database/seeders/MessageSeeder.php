<?php

namespace Database\Seeders;

use App\Helper\Functions;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Read messages file 
        $csvFile = fopen('database\seeders\GeneralistRails_Project_MessageData.csv', 'r');

        // Skip header row
        fgetcsv($csvFile);

        while (($data = fgetcsv($csvFile)) !== false) {
            $userId = $data[0];
            $time = $data[1];
            $messageBody = $data[2];

            Message::insert([
                'user_id' => $userId,
                'timestamp' => $time,
                'body' => $messageBody,
            ]);
        }

        fclose($csvFile);

        
    }
}
