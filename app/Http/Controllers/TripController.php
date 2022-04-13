<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TripRepository;
use Illuminate\Support\Facades\Validator;

class TripController extends Controller
{
    function __construct(TripRepository $trip)
    {
        $this->trip = $trip;
    }

    public function all()
     {
         try {
             $trips = $this->trip->all();

             return response(['data'=>$trips,'message'=>'trips fetched successfully']);
         } catch (\Exception $e) {
             info($e);
             return response(['message'=>'An error occured'],500);
         }
     }
    
    public function create(Request $request)
    {
        $v = Validator::make($request->all(),[
            'name'=>'required|unique:trips,name',
            'allocated_slots'=> 'required|numeric|min:1'
        ]);

        if($v->fails()){
            return response()->json(['message'=>$v->messages()],422);
        }

        try {
            $this->trip->create($request->all());
            return response(['message'=>'Trip created successfully']);

        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }

    public function update(Request $request,$id)
    {
        $v = Validator::make($request->all(),[
            'name'=>'required',
            'allocated_slots'=> 'required|numeric|min:1'
        ]);

        if($v->fails()){
            return response()->json(['message'=>$v->messages()],422);
        }

        try {
            $trip = $this->trip->get($id);
            
            if(! $trip){
                return response()->json(['message'=>'Trip not found'],404);
            }

            $data = ['id'=>$id,'name'=>$request->name,'slots'=>$request->allocated_slots];

            $this->trip->update($data);

            return response(['message'=>'Trip updated successfully']);

        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }

    public function show($id)
    {
        try {
            $trip = $this->trip->get($id);
            
            if(! $trip){
                return response(['message'=>'Trip not found'],404);
            }

            return response(['data'=>$trip,'message'=>'Trip retrieved successfully']);
        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }

    public function delete($id)
    {
        try {
           $this->trip->delete($id);

           return response(['message'=>'Trip deleted successfully']);

        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }

    public function totalReservations($id)
    {
        try {

            if(!$this->trip->get($id)){
                return response(['message'=>'Trip not found'],404);
            }

            $total = $this->trip->totalReservations($id);

            return response(['data'=>$total,'message'=>'Total trip reservation fetched successfully']);

        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }

    public function reservations($id)
    {
        try {

            if(!$this->trip->get($id)){
                return response(['message'=>'Trip not found'],404);
            }

            $reservations = $this->trip->reservations($id);

            return response(['data'=>$reservations,'message'=>'Trip reservation fetched successfully']);

        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }
}
