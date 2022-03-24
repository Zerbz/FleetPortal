<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    protected $guard = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'truck_id', 'placed_in_service', 'repair_completed', 'estimated_repair_date', 'repair_description', 'dispatcher_id', 'location', 'truck_number'
    ];
}
