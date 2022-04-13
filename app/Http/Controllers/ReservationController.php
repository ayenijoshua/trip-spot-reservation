<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ReservationRepository;
use App\Repositories\TripRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
     function __construct(ReservationRepository $reservation, TripRepository $trip, UserRepository $user)
     {
        $this->reservation = $reservation;
        $this->trip = $trip;
        $this->user = $user;
     }

    public function all()
    {
        try {
            $reserves = $this->reservation->all();

            return response(['data'=>$reserves,'message'=>'reservations fetched successfully']);
        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }

    public function show($id)
    {
        try {
            $reserves = $this->reservation->get($id);

            if(! $reserves){
                return response(['message'=>'Error, reservation not found'],404);
            }

            return response(['data'=>$reserves,'message'=>'reservation fetched successfully']);

        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }

    public function create(Request $request)
    {
        $v = Validator::make($request->all(),[
            'user_id'=>'required|numeric|exists:users,id',
            'trip_id'=>'required|numeric|exists:trips,id',
            'slots'=>'required|numeric|min:1'
        ]);

        if($v->fails()){
            return response()->json(['message'=>$v->messages()],422);
        }

        $trip_id = $request->trip_id;
        $slot_request = $request->slots;
        $user_id = $request->user_id;

        try {
            $reserved_spots = $this->reservation->reservedSpots($user_id,$trip_id);
            $newSpots =  $slot_request + ($reserved_spots ?? 0);

            $allocated_slots = $this->trip->get($trip_id)[0]->allocated_slots;

            $availale_slots = $this->reservation->slotDetails($trip_id)['available_slots'];

            if(! $allocated_slots){
                return response(['message'=>"Error, there are 0 spots available"],400);
            }

            if(! $this->reservation->tripHasReservation($trip_id) && $allocated_slots < $newSpots){
                return response(['message'=>"Error, there are n $allocated_slots spots available"],400);
            }

            if($this->reservation->tripHasReservation($trip_id) && $availale_slots < $slot_request){
                return response(['message'=>"Error, there are $availale_slots spots available"],400);
            }

            $reserved_spots == 0 && ! $this->user->reservations($user_id)
            ?  $this->reservation->create($request->all())
            :  $this->reservation->update(['user_id'=>$user_id,'slots'=>$newSpots]);
    
            $message = $request->slots.' slots reserved successfully';
            return response(['message'=>$message]);
        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
       
    }

    public function cancle(Request $request)
    {
        $v = Validator::make($request->all(),[
            'user_id'=>'required|numeric|exists:users,id',
            'trip_id'=>'required|numeric|exists:trips,id',
            'slots'=>'required|numeric|min:1'
        ]);

        if($v->fails()){
            return response()->json(['message'=>$v->messages()],422);
        }

        $trip_id = $request->trip_id;
        $slot_request = $request->slots;
        $user_id = $request->user_id;

        try {
            $reserved_spots = $this->reservation->reservedSpots($user_id,$trip_id);

            if($reserved_spots < $slot_request){
                return response(['message'=>"Error, you have $reserved_spots reserved spots"],400);
            }

            $newSpots = $reserved_spots - $slot_request;

            $this->reservation->cancleReservations($trip_id,$user_id,$newSpots);

            return response(['message'=>"$slot_request spots cancled successfully"]);
        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }

    public function delete($id)
    {
        try {
            if(! $this->reservation->get($id)){
                return response(['message'=>'Error, reservation not found'],404);
            }

            $this->reservation->delete($id);

            return response(['message'=>'Reservation deleted successfully']);

        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }
}
