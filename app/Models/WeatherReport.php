<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherReport extends Model
{
    protected $fillable = ['airport_id', 'temperature', 'humidity', 'wind_speed', 'weather', 'icon'];

    public function airport()
    {
        return $this->belongsTo(Airport::class);
    }
}
