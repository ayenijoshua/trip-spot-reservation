<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Trip;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TripTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function test_name_is_required_to_create_trip()
    {
        $trip = Trip::factory()->make([
            'name'=>null
        ])->toArray();
        $response = $this->postJson('/api/trips',$trip);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    }

    public function test_allocated_slots_is_required_to_create_trip()
    {
        $trip = Trip::factory()->make([
            'allocated_slots'=>null
        ])->toArray();
        $response = $this->postJson('/api/trips',$trip);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    }

    public function test_create_trip()
    {
        $trip = Trip::factory()->make()->toArray();
        $response = $this->postJson('/api/trips',$trip);

        $response->assertOk();
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseHas('trips',['name'=>$trip['name']]);
    }

    public function test_update_trip()
    {
        $old_trip = Trip::factory()->create(['name'=>'Trip to Africa','allocated_slots'=>12]);
        $trip = Trip::factory()->make(['name'=>'trip to America','allocated_slots'=>20])->toArray();

        $response = $this->putJson("/api/trips/{$old_trip->id}",$trip);

        $response->assertOk();
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseHas('trips',['name'=>$trip['name'],'allocated_slots'=>$trip['allocated_slots']]);
        $this->assertDatabaseMissing('trips',['name'=>$old_trip['name'],'allocated_slots'=>$old_trip['allocated_slots']]);
    }

    public function test_delete_trip()
    {
        $trip = Trip::factory()->create(['name'=>'Trip to Africa','allocated_slots'=>12]);

        $response = $this->deleteJson("/api/trips/{$trip->id}");

        $response->assertOk();
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseMissing('trips',['name'=>$trip['name'],'allocated_slots'=>$trip['allocated_slots']]);
    }

    public function test_get_all_trips()
    {
        Trip::factory()->count(10)->create();

        $response = $this->getJson("/api/trips");

        $response->assertOk();
        $response->assertJsonStructure(['data','message']);
        $this->assertDatabaseCount('trips',10);
    }

    public function test_get_single_trip()
    {
        $trip = Trip::factory()->create();

        $response = $this->getJson("/api/trips/{$trip->id}");

        $response->assertOk();
        $response->assertJsonStructure(['data','message']);
        $this->assertDatabaseCount('trips',1);
    }

    public function test_trip_not_found()
    {
        $trip = Trip::factory()->create();

        $response = $this->getJson("/api/trips/5");

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    public function test_get_all_reservation()
    {
        $trip = Trip::factory()->create();
        $users = User::factory()->count(5)->create();

        Reservation::factory()->create(['trip_id'=>$trip->id,'user_id'=>$users[0]->id,'slots'=>2]);
        Reservation::factory()->create(['trip_id'=>$trip->id,'user_id'=>$users[1]->id,'slots'=>3]);
        Reservation::factory()->create(['trip_id'=>$trip->id,'user_id'=>$users[2]->id,'slots'=>2]);
        Reservation::factory()->create(['trip_id'=>$trip->id,'user_id'=>$users[3]->id,'slots'=>2]);
        Reservation::factory()->create(['trip_id'=>$trip->id,'user_id'=>$users[4]->id,'slots'=>1]);

        $response = $this->getJson("/api/trips/{$trip->id}/reservations");

        $response1 = $this->getJson("/api/trips/{$trip->id}/total-reservations");

        $response->assertOk();
        $response->assertJsonStructure(['data','message']);
        $this->assertDatabaseCount('reservations',5);

        $total = (new \App\Repositories\TripRepository)->totalReservations($trip->id);

        $response1->assertOk();
        $response1->assertExactJson(['data'=>$total, 'message'=>'Total trip reservation fetched successfully']);
    }
}
