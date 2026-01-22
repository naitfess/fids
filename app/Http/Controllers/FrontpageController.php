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
        $flights = Flight::query()
        ->with(['user', 'origin', 'origin.weatherReport'])
        ->whereDate('scheduled_time', Carbon::today())
        ->orderBy('scheduled_time', 'desc')
        ->where('flight_type', 'arrival')
        ->paginate(10);

        $data['flights'] = $flights;
        $data['type'] = 'arrival';

        return view('frontpage.index', $data);
    }

    public function departure()
    {
        $flights = Flight::query()
        ->with(['user', 'destination', 'destination.weatherReport'])
        ->whereDate('scheduled_time', Carbon::today())
        ->orderBy('scheduled_time', 'desc')
        ->where('flight_type', 'departure')
        ->paginate(10);

        $data['flights'] = $flights;
        $data['type'] = 'departure';

        return view('frontpage.index', $data);
    }
}
