<?php
namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Models\Trip;

class TripRepository extends EloquentRepository implements RepositoryInterface
{
    public $trip;

    public function __construct(Trip $trip)
    {
        parent::__construct($trip);
        $this->trip = $trip;
    }

    /**
     * get role instance
     */
    public function getModel(){
        return $this->trip;
    }

    public function create(array $data)
    {
        return $this->trip->create($data);
    }

    
}