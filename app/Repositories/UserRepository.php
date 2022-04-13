<?php
namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository implements RepositoryInterface
{
    //public $user;

    // public function __construct(User $user)
    // {
    //     parent::__construct($user);
    //     $this->user = $user;
    // }

    // /**
    //  * get user instance
    //  */
    // public function getModel(){
    //     return $this->user;
    // }

    public function create(array $data)
    {
        $sql = "INSERT INTO users (`name`) VALUES (?)";
        $results = DB::select($sql,[$data['name']]);
        return $results;
    }

    public function update(array $data)
    {
        $sql = "UPDATE users SET `name` = ?
        WHERE `id` = ?";
        $results = DB::select($sql,[$data['name'], $data['id']]);
        return $results;
    }

    public function all()
    {
        $sql = "SELECT * FROM users";
        $results = DB::select($sql);
        return $results;
    }

    public function get($id)
    {
        $sql = "SELECT * FROM users 
        WHERE id = ?";
        $results = DB::select($sql,[$id]);
        return $results;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM users
        WHERE id = ?";
        $results = DB::select($sql,[$id]);
        return $results;
    }
    public function totalReservations($id)
    {
        $query = "SELECT SUM(reservations.slots) as total FROM reservations WHERE `user_id` = ?";
        $results = DB::select($query,[$id]);
        if(count($results) > 0 && ! property_exists($results[0],'total')){
            return $results[0]->total;
        }
        return 0;
    }

    public function reservations($id)
    {
        $query = "SELECT trips.name as trip_name, users.name as user, reservations.id as reserve_id, reservations.slots 
        FROM reservations 
        LEFT JOIN users on reservations.user_id = users.id
        LEFT JOIN trips on reservations.trip_id = trips.id
        WHERE reservations.user_id = ?";
        $results = DB::select($query,[$id]);
        return $results;
    }
}