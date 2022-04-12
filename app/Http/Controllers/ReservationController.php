<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ReservationRepository;

class ReservationController extends Controller
{
     function __construct(ReservationRepository $reservation)
     {
        $this->reservation = $reservation;
     }

    public function create(Request $request)
    {
        $request->validate([
            'user_id'=>'required|numeric',
            'trip_id'=>'required|numeric',
            'slots'=>'required|numeric'
        ]);

        $trip_id = $request->trip_id;
        $slot_request = $request->slots;
        $user_id = $request->user_id;

        try {
            $reserved_spots = $this->reservation->reservedSpots($user_id,$trip_id);
            $newSpots = $reserved_spots + $slot_request;

            if(! $this->reservation->tripHasAvailableSlots($trip_id, $newSpots)){
                $availale_slots = $this->reservation->slotDetails($trip_id)['available_slots'];
                return response(['message'=>"Error, there are $availale_slots available"]);
            }

            $reserved_spots == 0 
            ?  $this->reservation->create($request->all())
            :  $this->reservation->update(['user_id'=>$user_id,'slots'=>$newSpots]);
    
            //$this->reservation->create($request->all());
            $message = $request->slots.' slots reserved successfully';
            return response(['message'=>$message]);
        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
       
    }

    public function cancle(Request $request)
    {
        $request->validate([
            'user_id'=>'required|numeric',
            'trip_id'=>'required|numeric',
            'slots'=>'required|numeric'
        ]);

        $trip_id = $request->trip_id;
        $slot_request = $request->slots;
        $user_id = $request->user_id;

        try {
            $reserved_spots = $this->reservation->reservedSpots($user_id,$trip_id);

            if(! $reserved_spots >= $slot_request){
                return response(['message'=>"Error, you have $reserved_spots reserved spots"],400);
            }

            $newSpots = $reserved_spots - $slot_request;

            $this->reservation->cancleReservations($trip_id,$user_id,$newSpots);

            return response(['message'=>"$slot_request spots cacled successfully"]);
        } catch (\Exception $e) {
            info($e);
            return response(['message'=>'An error occured'],500);
        }
    }
}
