<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use App\Models\Message;
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
        $users = User::factory(10)->create();
        // Patient::factory(10)->create();
        // foreach($users as $user) {
        //         Patient::factory(1)->create([
        //             'name' => $user->name,
        //             'user_id' => $user->id
        //         ]);
        //         Message::factory(1)->create([
        //             'text' => 'Hello, I am ' . $user->name . ' and I am a ' . ($user->is_patient ? 'patient' : 'doctor') . '.',
        //             'sender' => $user->id,
        //             //receiver is random user who is not the sender
        //             'receiver' => $users->where('id', '!=', $user->id)->random()->id,
        //             'attachement' => 'image'
        //         ]);
        // }
    }
}
