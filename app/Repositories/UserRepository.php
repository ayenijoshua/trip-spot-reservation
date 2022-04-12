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
        $results = DB::select($sql,[$data['name']]);
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
}