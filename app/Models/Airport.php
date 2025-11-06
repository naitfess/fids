<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = ['code', 'name', 'area_code', 'longitude', 'latitude'];

    public function departures()
    {
        return $this->hasMany(Flight::class, 'origin_id');
    }

    public function arrivals()
    {
        return $this->hasMany(Flight::class, 'destination_id');
    }

    public function weatherReport()
    {
        return $this->hasOne(WeatherReport::class);
    }
}
