<?php
namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;

class TripRepository implements RepositoryInterface
{
    // public $trip;

    // public function __construct(Trip $trip)
    // {
    //     parent::__construct($trip);
    //     $this->trip = $trip;
    // }

    // /**
    //  * get role instance
    //  */
    // public function getModel(){
    //     return $this->trip;
    // }

    public function create($data)
    {
        $sql = "INSERT INTO trips (`name`,`allocated_slots`) VALUES (?,?)";
        $results = DB::select($sql,[$data['name'],$data['allocated_slots']]);
        return $results;
    }

    public function update($data)
    {
        $sql = "UPDATE trips SET `name` = ? , allocated_slots = ?
        WHERE `id` = ?";
        $results = DB::select($sql,[$data['name'],$data['slots']]);
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
        $sql = "SELECT * FROM trips 
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

    
}