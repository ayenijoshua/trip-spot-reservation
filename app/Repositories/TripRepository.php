<?php
namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;

class TripRepository implements RepositoryInterface
{

    public function create(array $data)
    {
        $sql = "INSERT INTO trips (`name`,`allocated_slots`) VALUES (?,?)";
        $results = DB::select($sql,[$data['name'],$data['allocated_slots']]);
        return $results;
    }

    public function update(array $data)
    {
        $sql = "UPDATE trips SET `name` = ? , allocated_slots = ?
        WHERE `id` = ?";
        $results = DB::select($sql,[$data['name'],$data['slots'],$data['id']]);
        return $results;
    }

    public function all()
    {
        $sql = "SELECT * FROM trips";
        $results = DB::select($sql);
        return $results;
    }

    public function get($id)
    {
        $sql = "SELECT trips.name, trips.allocated_slots, 
        (trips.allocated_slots - (SELECT SUM(reservations.slots) FROM reservations WHERE trip_id = $id)) as available_slots,
        (SELECT SUM(reservations.slots) FROM reservations WHERE trip_id = $id) as reserved_slots
        FROM trips 
        WHERE id = ?";
        $results = DB::select($sql,[$id]);
        return $results;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM trips
        WHERE id = ?";
        $results = DB::select($sql,[$id]);
        return $results;
    }

    public function totalReservations($trip_id)
    {
        $query = "SELECT SUM(reservations.slots) as total FROM reservations WHERE trip_id = ?";
        $results = DB::select($query,[$trip_id]);
        if(count($results) > 0 && property_exists($results[0],'total')){
            return $results[0]->total;
        }
        return 0;
    }

    public function reservations($trip_id)
    {
        $query = "SELECT trips.name as trip_name, users.name as user, reservations.id as reserve_id, reservations.slots 
        FROM reservations 
        LEFT JOIN users on reservations.user_id = users.id
        LEFT JOIN trips on reservations.trip_id = trips.id
        WHERE reservations.trip_id = ?";
        $results = DB::select($query,[$trip_id]);
        return $results;
    }

    
}