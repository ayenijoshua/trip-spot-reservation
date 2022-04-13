<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Trip;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function test_name_is_required_to_create_user()
    {
        $user = User::factory()->make([
            'name'=>null
        ])->toArray();
        $response = $this->postJson('/api/users',$user);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    }

    public function test_create_user()
    {
        $user = User::factory()->make()->toArray();
        $response = $this->postJson('/api/users',$user);

        $response->assertOk();
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseHas('users',['name'=>$user['name']]);
    }

    public function test_update_user()
    {
        $old_user = User::factory()->create(['name'=>'Joshua']);
        $user = User::factory()->make(['name'=>'James'])->toArray();

        $response = $this->putJson("/api/users/{$old_user->id}",$user);

        $response->assertOk();
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseHas('users',['name'=>$user['name']]);
        $this->assertDatabaseMissing('users',['name'=>$old_user['name']]);
    }

    public function test_delete_user()
    {
        $user = User::factory()->create(['name'=>'James']);

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertOk();
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseMissing('users',['name'=>$user['name']]);
    }

    public function test_get_all_users()
    {
        User::factory()->count(10)->create();

        $response = $this->getJson("/api/users");

        $response->assertOk();
        $response->assertJsonStructure(['data','message']);
        $this->assertDatabaseCount('users',10);
    }

    public function test_get_single_user()
    {
        $User = User::factory()->create();

        $response = $this->getJson("/api/users/{$User->id}");

        $response->assertOk();
        $response->assertJsonStructure(['data','message']);
        $this->assertDatabaseCount('users',1);
    }

    public function test_user_not_found()
    {
        User::factory()->create();

        $response = $this->getJson("/api/users/5");

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    public function test_get_all_reservation()
    {
        $trips = Trip::factory()->count(5)->create();
        $user = User::factory()->create();

        Reservation::factory()->create(['trip_id'=>$trips[0]->id,'user_id'=>$user->id,'slots'=>2]);
        Reservation::factory()->create(['trip_id'=>$trips[1]->id,'user_id'=>$user->id,'slots'=>3]);
        Reservation::factory()->create(['trip_id'=>$trips[2]->id,'user_id'=>$user->id,'slots'=>2]);
        Reservation::factory()->create(['trip_id'=>$trips[3]->id,'user_id'=>$user->id,'slots'=>2]);
        Reservation::factory()->create(['trip_id'=>$trips[4]->id,'user_id'=>$user->id,'slots'=>1]);

        $response = $this->getJson("/api/users/{$user->id}/reservations");

        $response1 = $this->getJson("/api/users/{$user->id}/total-reservations");

        $response->assertOk();
        $response->assertJsonStructure(['data','message']);
        $this->assertDatabaseCount('reservations',5);

        $total = (new \App\Repositories\TripRepository)->totalReservations($user->id);

        $response1->assertOk();
        $response1->assertExactJson(['data'=>$total, 'message'=>'Total user reservation fetched successfully']);
    }
}
