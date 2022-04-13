<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Reservation;
use App\Models\Trip;
use App\Models\User;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */

    public function test_tripId_is_required_to_create_reservation()
    {
        $response = $this->postJson('/api/reservations',[]);

        $response->assertStatus(422);
        
    }

    public function test_trip_has_allocated_slots_to_create_reservation()
    {
        $trip = Trip::factory()->create(['allocated_slots'=>0]);
        $user = User::factory()->create();
        $data = ['trip_id'=>$trip->id,'user_id'=>$user->id, 'slots'=>2];

        $response = $this->postJson('/api/reservations',$data);

        $response->assertStatus(400);
        $response->assertExactJson(['message'=>'Error, there are 0 spots available']);
    }

    public function test_trip_has_available_slots_to_create_reservation()
    {
        $trip = Trip::factory()->create();
        $user = User::factory()->create();

        Reservation::factory()->create(['trip_id'=>$trip->id,'user_id'=>$user->id,'slots'=>5]);

        $data = ['trip_id'=>$trip->id,'user_id'=>$user->id, 'slots'=>10];

        $response = $this->postJson('/api/reservations',$data);

        $available_slots = (new \App\Repositories\ReservationRepository)->slotDetails($trip->id)['available_slots'];

        $response->assertStatus(400);
        $response->assertExactJson(['message'=>"Error, there are $available_slots spots available"]);
    }

    public function test_slot_canclation()
    {
        $trip = Trip::factory()->create();
        $user = User::factory()->create();

        $reservation = Reservation::factory()->create(['trip_id'=>$trip->id,'user_id'=>$user->id,'slots'=>5]);

        $data = ['trip_id'=>$trip->id,'user_id'=>$user->id, 'slots'=>2];

        $response = $this->putJson('/api/reservations/canclation',$data);
        $cancled_slots = $data['slots'];

        $calculated_available_slots = $trip->allocated_slots - ($reservation->slots - $cancled_slots);

        $available_slots = (new \App\Repositories\ReservationRepository)->slotDetails($trip->id)['available_slots'];

        $response->assertOk();
        $response->assertExactJson(['message'=>"$cancled_slots spots cancled successfully"]);

        $this->assertEquals($available_slots,$calculated_available_slots);
    }

    public function test_get_user_reservations()
    {
        $trip = Trip::factory()->create();
        $user = User::factory()->create();

        $reservation = Reservation::factory()->create(['trip_id'=>$trip->id,'user_id'=>$user->id,'slots'=>5]);

        $data = ['trip_id'=>$trip->id,'user_id'=>$user->id, 'slots'=>2];

        $calculated_reserved_slots = $reservation->slots + $data['slots'];

        $response = $this->postJson('/api/reservations',$data);

        $response->assertOk();

        $reserved_slots = (new \App\Repositories\ReservationRepository)->reservedSpots($user->id,$trip->id);

        $this->assertEquals($reserved_slots,$calculated_reserved_slots);
    }

    public function test_get_all_reservation()
    {
        $trips = Trip::factory()->count(3)->create();
        $users = User::factory()->count(5)->create();

        Reservation::factory()->create(['trip_id'=>$trips[0]->id,'user_id'=>$users[0]->id,'slots'=>2]);
        Reservation::factory()->create(['trip_id'=>$trips[0]->id,'user_id'=>$users[1]->id,'slots'=>3]);
        Reservation::factory()->create(['trip_id'=>$trips[1]->id,'user_id'=>$users[2]->id,'slots'=>2]);
        Reservation::factory()->create(['trip_id'=>$trips[2]->id,'user_id'=>$users[3]->id,'slots'=>2]);
        Reservation::factory()->create(['trip_id'=>$trips[1]->id,'user_id'=>$users[4]->id,'slots'=>1]);

        $response = $this->getJson('/api/reservations');

        $response->assertOk();
        $response->assertJsonStructure(['data','message']);
        $this->assertDatabaseCount('reservations',5);
    }

    public function test_get_single_reservation()
    {
        $trip = Trip::factory()->create();
        $user = User::factory()->create();

        $reservation = Reservation::factory()->create(['trip_id'=>$trip->id,'user_id'=>$user->id,'slots'=>2]);

        $response = $this->getJson("api/reservations/{$reservation->id}");

        $response->assertOk();
        $response->assertJsonStructure(['data','message']);
    }

    public function test_delete_reservation()
    {
        $trip = Trip::factory()->create();
        $user = User::factory()->create();

        $reservation = Reservation::factory()->create(['trip_id'=>$trip->id,'user_id'=>$user->id,'slots'=>2]);

        $response = $this->deleteJson("api/reservations/{$reservation->id}");

        $response->assertOk();
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseMissing('reservations',['id'=>$reservation->id]);
    }
}
