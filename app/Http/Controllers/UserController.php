<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class UserController extends Controller
{

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }


    public function create(Request $request)
    {
        $request->validate(['name'=>'required']);

        try {
            $this->user->create($request->all());

            return response(['message'=>'user created successfully']);

        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }

    public function update(Request $request)
    {
        $request->validate(['name'=>'required|unique:users,name']);

        try {
            $this->user->update($request->all());

            return response(['message'=>'user created successfully']);

        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }

    public function show()
    {
        
    }

    public function delete($id)
    {
        try {
            $this->user->delete($id);

            return response(['message'=>'User deleted successfully']);
            
        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }
}
