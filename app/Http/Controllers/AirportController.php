<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function index()
    {
        $data['airports'] = Airport::paginate(10);
        return view('admin.airport.index', $data);
    }
}
