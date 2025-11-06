<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FrontpageController extends Controller
{
    public function index()
    {
        $base = Flight::query()
        ->with(['origin', 'destination', 'origin.weatherReport', 'destination.weatherReport'])
        ->whereDate('scheduled_time', Carbon::today())
        ->orderBy('scheduled_time', 'desc');

        $data['arrival_flights'] = (clone $base)->where('flight_type', 'arrival')->paginate(10, ['*'], 'arrival_page');
        $data['departure_flights'] = (clone $base)->where('flight_type', 'departure')->paginate(10, ['*'], 'departure_page');

        return view('frontpage.index', $data);
    }
}
