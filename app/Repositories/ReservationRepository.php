<?php
namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class ReservationRepository implements RepositoryInterface
{
    public function all()
    {
        $sql = "SELECT reservations.id as reserve_id, reservations.slots, users.name, trips.name as trip_name
        FROM reservations 
        LEFT JOIN users on reservations.user_id = users.id
        LEFT JOIN trips on reservations.trip_id = trips.id
        GROUP BY users.name";
        $results = DB::select($sql);
        return $results;
    }

    public function get($id)
    {
        $sql = "SELECT reservations.id as reserve_id, reservations.slots, users.name, trips.name as trip_name
        FROM reservations 
        LEFT JOIN users on reservations.user_id = users.id
        LEFT JOIN trips on reservations.trip_id = trips.id
        WHERE reserve_id = ?
        GROUP BY users.name";
        $results = DB::select($sql,[$id]);
        return $results;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM reservations
        WHERE id = ?";
        $results = DB::select($sql,[$id]);
        return $results;
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO reservations (`trip_id`,`user_id`,`slots`) VALUES (?,?,?)";
        $results = DB::select($sql,[$data['trip_id'],$data['user_id'],$data['slots']]);
        return $results;
    }

    public function update($data)
    {
        $sql = "UPDATE reservations SET slots = ? 
        WHERE `user_id` = ?";
        $results = DB::select($sql,[$data['slots'],$data['user_id']]);
        return $results;
    }

    public function tripHasAvailableSlots($trip_id,$slot_request)
    {
        $query = "SELECT (trips.allocated_slots - SUM('reservations.slots')) as available_slots, trips.allocated_slots
        FROM reservations
        LEFT JOIN trips on trips.id = reservations.trip_id
        WHERE trips.id = ?";
        $results = DB::select($query,[$trip_id]);
        if($results['available_slots'] >= $slot_request){
            return true;
        }
        return false;
    }

    public function slotDetails($trip_id)
    {
        $query = "SELECT (trips.allocated_slots - SUM('reservations.slots')) as available_slots, trips.allocated_slots
        FROM reservations
        LEFT JOIN trips on trips.id = reservations.trip_id
        WHERE trips.id = ?";
        $results = DB::select($query,[$trip_id]);
        return $results;
    }

    public function userHasReservations($user_id,$trip_id)
    {
        $query = "SELECT reservations.slots as spots
        FROM reservations
        WHERE trip_id = ? AND reservations.user_id = ?";
        $results = DB::select($query,[$trip_id,$user_id]);
        if($results['spots'] > 0){
            return true;
        }
        return false;
    }

    public function reservedSpots($user_id,$trip_id)
    {
        $query = "SELECT reservations.slots as spots
        FROM reservations
        WHERE trip_id = ? AND reservations.user_id = ?";
        $results = DB::select($query,[$trip_id,$user_id]);
        return $results['spots'];
    }

    public function cancleReservations($trip_id,$user_id,$slot)
    {
        $query = "UPDATE reservations
        SET slots = ?
        WHERE trip_id = ? AND reservations.user_id = ?";
        $results = DB::select($query,[$slot,$trip_id,$user_id]);
        return $results;
    }

    
}