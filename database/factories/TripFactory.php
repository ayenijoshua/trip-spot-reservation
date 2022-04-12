<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => array('berlin trip','us trip','munich trip')[array_rand([0,1,2],1)],//$this->faker->randomElement(['berlin trip','us trip']),
            'allocated_slots' => 10
        ];
    }

}
