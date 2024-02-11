<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $priorities = [
            ['name' => 'Low'],
            ['name' => 'High'],
            ['name' => 'Highest'],
        ];

        Priority::insert($priorities);
    }
}
