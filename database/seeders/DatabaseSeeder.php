<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Co2;
use App\Models\Humidity;
use App\Models\Movement;
use App\Models\Temperature;
use App\Models\Block;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //create admin account
        \App\Models\User::factory(1)->create();
        Patient::factory(10)->create();
    }
}
