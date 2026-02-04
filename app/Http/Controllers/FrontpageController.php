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
            ->orderBy('scheduled_time', 'asc')
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
            ->orderBy('scheduled_time', 'asc')
            ->where('flight_type', 'departure')
            ->paginate(10);

        $data['flights'] = $flights;
        $data['type'] = 'departure';

        return view('frontpage.index', $data);
    }

    public function checkUpdates(Request $request)
    {
        $type = $request->query('type', 'arrival');

        $totalCount = Flight::query()
            ->whereDate('scheduled_time', Carbon::today())
            ->where('flight_type', $type)
            ->count();

        $lastUpdated = Flight::query()
            ->whereDate('scheduled_time', Carbon::today())
            ->where('flight_type', $type)
            ->max('updated_at');

        // Handle case when no flights exist
        if ($lastUpdated === null) {
            $lastUpdated = '0';
        }

        // Include total count in checksum to detect deletions
        $checksum = hash('sha256', $totalCount . '|' . $lastUpdated);

        return response()->json([
            'checksum' => $checksum,
            'count' => $totalCount
        ]);
    }
}
