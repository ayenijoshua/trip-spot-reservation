<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ReservationRepository;
use phpDocumentor\Reflection\Types\This;

class ReservationController extends Controller
{
     function __construct(ReservationRepository $reservation)
     {
        $this->reservation = $reservation;
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
        $request->validate([
            'user_id'=>'required|numeric|exists:users,id',
            'trip_id'=>'required|numeric|exists:trips,id',
            'slots'=>'required|numeric|min:1'
        ]);

        $trip_id = $request->trip_id;
        $slot_request = $request->slots;
        $user_id = $request->user_id;

        try {
            $reserved_spots = $this->reservation->reservedSpots($user_id,$trip_id);
            $newSpots = $reserved_spots + $slot_request;

            if(! $this->reservation->tripHasAvailableSlots($trip_id, $newSpots)){
                $availale_slots = $this->reservation->slotDetails($trip_id)['available_slots'];
                return response(['message'=>"Error, there are $availale_slots spots available"],400);
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
            'user_id'=>'required|numeric|exists:users,id',
            'trip_id'=>'required|numeric|exists:trips,id',
            'slots'=>'required|numeric|min:1'
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
