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