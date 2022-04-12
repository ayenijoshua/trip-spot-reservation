<?php
namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class ReservationRepository extends EloquentRepository implements RepositoryInterface
{
    public $reservation;

    public function __construct(Reservation $reservation)
    {
        parent::__construct($reservation);
        $this->reservation = $reservation;
    }

    /**
     * get role instance
     */
    public function getModel(){
        return $this->reservation;
    }

    public function create(array $data)
    {
        $this->reservation->create($data);
    }

    public function tripHasAvailableSlots($trip_id,$slot_request)
    {
        $query = "SELECT (trips.allocated_slots - SUM('reservations.slots')) as available_slots, trips.allocated_slots
        FROM reservations
        LEFT JOIN trips on trips.id = reservations.trip_id
        WHERE trips.id = ?";
        $results = DB::select($query,[$trip_id]);
        if($results['available_slots'] >= $slot_request){
            return true;
        }
        return false;
    }

    public function slotDetails($trip_id)
    {
        $query = "SELECT (trips.allocated_slots - SUM('reservations.slots')) as available_slots, trips.allocated_slots
        FROM reservations
        LEFT JOIN trips on trips.id = reservations.trip_id
        WHERE trips.id = ?";
        $results = DB::select($query,[$trip_id]);
        return $results;
    }

    public function userHasReservations($user_id,$trip_id)
    {
        $query = "SELECT reservations.slots as spots
        FROM reservations
        WHERE trip_id = ? AND reservations.user_id = ?";
        $results = DB::select($query,[$trip_id,$user_id]);
        if($results['spots'] > 0){
            return true;
        }
        return false;
    }

    public function reservedSpots($user_id,$trip_id)
    {
        $query = "SELECT reservations.slots as spots
        FROM reservations
        WHERE trip_id = ? AND reservations.user_id = ?";
        $results = DB::select($query,[$trip_id,$user_id]);
        return $results['spots'];
    }

    public function cancleReservations($trip_id,$user_id,$slot)
    {
        $query = "UPDATE reservations
        SET (slots) VALUES (?)
        WHERE trip_id = ? AND reservations.user_id = ?";
        $results = DB::select($query,[$slot,$trip_id,$user_id]);
        return $results;
    }

    
}