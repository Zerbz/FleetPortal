<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Load extends Model
{
    protected $guard = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'load_number', 'dispatcher_id', 'driver_id', 'truck_id', 'company', 'company_phone', 'contact', 'po_number', 'pickup_date', 'delivery_date', 'price', 'load_notes', 'delivery_notes', 'load_feedback', 'load_file'
    ];
}
