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
        $uniqueIds = Message::distinct()->pluck('user_id');
        $uniqueIdsArray = $uniqueIds->map(function ($userId) {
            return ['id' => $userId];
        });   
        // dd($uniqueIdsArray);
        User::insert($uniqueIdsArray->toArray());

    }
}
