<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TripRepository;

class TripController extends Controller
{
    function __construct(TripRepository $trip)
    {
        $this->trip = $trip;
    }
    
    public function create(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'allocated_slots'=> 'required|numeric'
        ]);

        try {
            $this->trip->create($request->all());
            return response(['message'=>'Trip created successfully']);
        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'allocated_slots'=> 'required|numeric'
        ]);

        try {
            $this->trip->update($request->all());
            
            return response(['message'=>'Trip updated successfully']);

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
           $this->trip->delete($id);

           return response(['message'=>'Trip deleted successfully']);

        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }
}
