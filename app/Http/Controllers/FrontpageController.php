<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Flight;
use App\Models\RunningText;
use Illuminate\Http\Request;

class FrontpageController extends Controller
{
    public function index()
    {
        $arrivals = Flight::query()
            ->with(['user', 'origin', 'origin.weatherReport'])
            ->whereDate('scheduled_time', Carbon::today())
            ->orderBy('scheduled_time', 'asc')
            ->where('flight_type', 'arrival')
            ->limit(4)
            ->get();

        $departures = Flight::query()
            ->with(['user', 'destination', 'destination.weatherReport'])
            ->whereDate('scheduled_time', Carbon::today())
            ->orderBy('scheduled_time', 'asc')
            ->where('flight_type', 'departure')
            ->limit(4)
            ->get();

        $runningTexts = RunningText::query()
            ->latest()
            ->pluck('text')
            ->values();

        return view('frontpage.index', [
            'arrivals' => $arrivals,
            'departures' => $departures,
            'runningTexts' => $runningTexts,
        ]);
    }

    public function departure()
    {
        return $this->index();
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

    public function checkRunningTextUpdates()
    {
        $totalCount = RunningText::query()->count();
        $lastUpdated = RunningText::query()->max('updated_at');

        if ($lastUpdated === null) {
            $lastUpdated = '0';
        }

        $checksum = hash('sha256', $totalCount . '|' . $lastUpdated);

        return response()->json([
            'checksum' => $checksum,
            'count' => $totalCount,
        ]);
    }

    public function runningTexts()
    {
        $messages = RunningText::query()
            ->pluck('text')
            ->filter()
            ->values();

        $totalCount = $messages->count();
        $lastUpdated = RunningText::query()->max('updated_at') ?? '0';
        $checksum = hash('sha256', $totalCount . '|' . $lastUpdated);

        return response()->json([
            'checksum' => $checksum,
            'messages' => $messages,
        ]);
    }
}
