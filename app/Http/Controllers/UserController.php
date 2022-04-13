<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function all()
     {
         try {
             $users = $this->user->all();

             return response(['data'=>$users,'message'=>'users fetched successfully']);
         } catch (\Exception $e) {
             info($e);
             return response(['message'=>'An error occured'],500);
         }
     }


    public function create(Request $request)
    {
        $v = Validator::make($request->all(),['name'=>'required']);

        if($v->fails()){
            return response()->json(['message'=>$v->messages()],422);
        }
        
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
        $v = Validator::make($request->all(),['name'=>'required|unique:users,name']);

        if($v->fails()){
            return response()->json(['message'=>$v->messages()],422);
        }

        try {
            $data = ['name'=>$request->name,'id'=>$request->id];

            $this->user->update($data);

            return response(['message'=>'user updated successfully']);

        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }

    public function show($id)
    {
        try {
            $user = $this->user->get($id);
            if(!$user){
                return response(['message'=>'user not found'],404);
            }

            return response(['data'=>$user,'message'=>'user retrieved successfully']);

        } catch (\Exception $e) {
            info($e);
        }
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

    public function totalReservations($id)
    {
        try {

            if(!$this->user->get($id)){
                return response(['message'=>'User not found'],404);
            }

            $total = $this->user->totalReservations($id) ?? 0;

            return response(['data'=>$total,'message'=>'Total user reservation fetched successfully']);

        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }

    public function reservations($id)
    {
        try {

            if(!$this->user->get($id)){
                return response(['message'=>'User not found'],404);
            }

            $reservations = $this->user->reservations($id);

            return response(['data'=>$reservations,'message'=>'User reservation fetched successfully']);

        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }
}
