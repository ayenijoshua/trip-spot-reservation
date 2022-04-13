<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Reservation;
use \App\Models\User;
use \App\Models\Trip;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\Models\User::factory(5)->create();
        $trips = \App\Models\Trip::factory(3)->create();
        Reservation::factory()->create(['trip_id'=>$trips[0]->id,'user_id'=>$users[0]->id,'slots'=>2]);
        Reservation::factory()->create(['trip_id'=>$trips[0]->id,'user_id'=>$users[1]->id,'slots'=>3]);
        Reservation::factory()->create(['trip_id'=>$trips[1]->id,'user_id'=>$users[2]->id,'slots'=>2]);
        Reservation::factory()->create(['trip_id'=>$trips[2]->id,'user_id'=>$users[3]->id,'slots'=>2]);
        Reservation::factory()->create(['trip_id'=>$trips[1]->id,'user_id'=>$users[4]->id,'slots'=>1]);
    }
}
