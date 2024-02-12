<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Support Agents 
        for ($i = 1; $i <= 10; $i++) {
            Agent::create(['id' => $i]);
        }
        // Customer users
        $csvFile = fopen('database\seeders\GeneralistRails_Project_MessageData.csv', 'r');
        // Skip the header row
        fgetcsv($csvFile);
        $uniqueUserIds = [];
        //  Unique id save
        while (($data = fgetcsv($csvFile)) !== false) {
            $userId = $data[0]; 
            $uniqueUserIds[$userId] = true; 
        }
        fclose($csvFile);

        // Insert unique user_id values into the users table
        foreach (array_keys($uniqueUserIds) as $userId) {
            User::firstOrCreate(['id' => $userId]);
        }

    }
}
