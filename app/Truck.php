<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $guard = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'truck_number', 'make', 'model', 'year', 'phone', 'mileage', 'dispatcher_id', 'plate_number', 'in_use', 'in_service'
    ];
}
