<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlightController extends Controller
{
    public function index()
    {
        $base = Flight::with(['origin', 'destination'])->orderBy('scheduled_time', 'desc')->where('user_id', auth()->id());

        $data['arrival_flights'] = (clone $base)->where('flight_type', 'arrival')->paginate(10, ['*'], 'arrival_page');
        $data['departure_flights'] = (clone $base)->where('flight_type', 'departure')->paginate(10, ['*'], 'departure_page');

        return view('flight.index', $data);
    }

    public function create(Request $request)
    {
        if ($request->query('flight_type') && !in_array($request->query('flight_type'), ['arrival', 'departure'])) {
            abort(404);
        }
        $data['flight_type'] = $request->query('flight_type', 'departure');
        $data['airports'] = Airport::get();
        return view('flight.create', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'flight_number' => 'required|string|max:10|unique:flights,flight_number',
            'flight_type' => 'required|in:arrival,departure',
            'origin' => 'required_if:flight_type,arrival|exists:airports,id',
            'destination' => 'required_if:flight_type,departure|exists:airports,id',
            'gate' => 'required|integer|min:1|max:5',
            'scheduled_time' => 'required|date_format:Y-m-d\TH:i',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'user_id' => auth()->id(),
            'flight_number' => $request->input('flight_number'),
            'flight_type' => $request->input('flight_type'),
            'origin_id' => $request->input('flight_type') === 'arrival' ? $request->input('origin') : null,
            'destination_id' => $request->input('flight_type') === 'departure' ? $request->input('destination') : null,
            'gate' => $request->input('gate'),
            'scheduled_time' => $request->input('scheduled_time'),
        ];

        Flight::create($data);

        return redirect()->route('flight.index')->with([
            'status' => 'success',
            'message' => 'Flight created successfully',
        ]);
    }

    public function edit($flightId)
    {
        $flight = Flight::findOrFail($flightId);
        $data['flight'] = $flight;
        $data['airports'] = Airport::get();
        return view('flight.edit', $data);
    }

    public function update($flightId, Request $request)
    {
        $flight = Flight::findOrFail($flightId);

        $rules = [
            'flight_number' => 'required|string|max:10|unique:flights,flight_number,' . $flight->id,
            'flight_type' => 'in:arrival,departure',
            'origin' => 'required_if:flight_type,arrival|exists:airports,id',
            'destination' => 'required_if:flight_type,departure|exists:airports,id',
            'gate' => 'required|integer|min:1|max:5',
            'scheduled_time' => 'required|date_format:Y-m-d\TH:i',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'flight_number' => $request->input('flight_number'),
            'flight_type' => $flight->flight_type,
            'origin_id' => $flight->flight_type === 'arrival' ? $request->input('origin') : null,
            'destination_id' => $flight->flight_type === 'departure' ? $request->input('destination') : null,
            'gate' => $request->input('gate'),
            'scheduled_time' => $request->input('scheduled_time'),
        ];

        $flight->update($data);

        return redirect()->route('flight.index')->with([
            'status' => 'success',
            'message' => 'Flight updated successfully',
        ]);
    }

    public function changeStatus($flightId, Request $request)
    {
        $flight = Flight::findOrFail($flightId);

        $statuses = [
            'Check-in Open',
            'Check-in Closed',
            'Boarding',
            'Final Call',
            'Departed',
            'Delayed',
            'Cancelled',
        ];

        $rules = [
            'status' => 'required|string|in:' . implode(',', $statuses),
        ];

        if ($request->input('status') === 'Delayed') {
            $rules['delayed_until'] = 'required|date_format:Y-m-d\TH:i|after:now';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = [
            'status' => $request->input('status'),
        ];

        if ($request->input('status') === 'Delayed') {
            $data['delayed_until'] = $request->input('delayed_until');
        } else {
            $data['delayed_until'] = null;
        }
        $flight->update($data);

        session()->flash('status', 'success');
        session()->flash('message', 'Flight status updated successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Flight status updated successfully',
        ]);
    }

    public function destroy($flightId)
    {
        $flight = Flight::findOrFail($flightId);

        $flight->delete();

        return redirect()->route('flight.index')->with([
            'status' => 'success',
            'message' => 'Flight deleted successfully',
        ]);
    }

    //admin
    public function adminIndex()
    {
        $base = Flight::with(['origin', 'destination'])->orderBy('scheduled_time', 'desc');

        $data['arrival_flights'] = (clone $base)->where('flight_type', 'arrival')->paginate(10, ['*'], 'arrival_page');
        $data['departure_flights'] = (clone $base)->where('flight_type', 'departure')->paginate(10, ['*'], 'departure_page');

        return view('admin.flight.index', $data);
    }

    public function adminCreate(Request $request)
    {
        if ($request->query('flight_type') && !in_array($request->query('flight_type'), ['arrival', 'departure'])) {
            abort(404);
        }
        $data['users'] = User::where('role', 'staff')->get();
        $data['flight_type'] = $request->query('flight_type', 'departure');
        $data['airports'] = Airport::get();
        return view('admin.flight.create', $data);
    }

    public function adminStore(Request $request)
    {
        $rules = [
            'airlane' => 'required|exists:users,id',
            'flight_number' => 'required|string|max:10|unique:flights,flight_number',
            'flight_type' => 'required|in:arrival,departure',
            'origin' => 'required_if:flight_type,arrival|exists:airports,id',
            'destination' => 'required_if:flight_type,departure|exists:airports,id',
            'gate' => 'required|integer|min:1|max:5',
            'scheduled_time' => 'required|date_format:Y-m-d\TH:i',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'user_id' => $request->input('airlane'),
            'flight_number' => $request->input('flight_number'),
            'flight_type' => $request->input('flight_type'),
            'origin_id' => $request->input('flight_type') === 'arrival' ? $request->input('origin') : null,
            'destination_id' => $request->input('flight_type') === 'departure' ? $request->input('destination') : null,
            'gate' => $request->input('gate'),
            'scheduled_time' => $request->input('scheduled_time'),
        ];

        Flight::create($data);

        return redirect()->route('admin.flight.index')->with([
            'status' => 'success',
            'message' => 'Flight created successfully',
        ]);
    }

    public function adminEdit($flightId)
    {
        $flight = Flight::findOrFail($flightId);
        $data['flight'] = $flight;
        $data['airports'] = Airport::get();
        $data['users'] = User::where('role', 'staff')->get();
        return view('admin.flight.edit', $data);
    }

    public function adminUpdate($flightId, Request $request)
    {
        $flight = Flight::findOrFail($flightId);

        $rules = [
            'flight_number' => 'required|string|max:10|unique:flights,flight_number,' . $flight->id,
            'flight_type' => 'in:arrival,departure',
            'origin' => 'required_if:flight_type,arrival|exists:airports,id',
            'destination' => 'required_if:flight_type,departure|exists:airports,id',
            'gate' => 'required|integer|min:1|max:5',
            'scheduled_time' => 'required|date_format:Y-m-d\TH:i',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'flight_number' => $request->input('flight_number'),
            'flight_type' => $flight->flight_type,
            'origin_id' => $flight->flight_type === 'arrival' ? $request->input('origin') : null,
            'destination_id' => $flight->flight_type === 'departure' ? $request->input('destination') : null,
            'gate' => $request->input('gate'),
            'scheduled_time' => $request->input('scheduled_time'),
        ];

        $flight->update($data);

        return redirect()->route('admin.flight.index')->with([
            'status' => 'success',
            'message' => 'Flight updated successfully',
        ]);
    }

    public function adminChangeStatus($flightId, Request $request)
    {
        $flight = Flight::findOrFail($flightId);

        $statuses = [
            'Check-in Open',
            'Check-in Closed',
            'Boarding',
            'Final Call',
            'Departed',
            'Delayed',
            'Cancelled',
        ];

        $rules = [
            'status' => 'required|string|in:' . implode(',', $statuses),
        ];

        if ($request->input('status') === 'Delayed') {
            $rules['delayed_until'] = 'required|date_format:Y-m-d\TH:i|after:now';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = [
            'status' => $request->input('status'),
        ];

        if ($request->input('status') === 'Delayed') {
            $data['delayed_until'] = $request->input('delayed_until');
        } else {
            $data['delayed_until'] = null;
        }
        $flight->update($data);

        session()->flash('status', 'success');
        session()->flash('message', 'Flight status updated successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Flight status updated successfully',
        ]);
    }

    public function adminDestroy($flightId)
    {
        $flight = Flight::findOrFail($flightId);

        $flight->delete();

        return redirect()->route('admin.flight.index')->with([
            'status' => 'success',
            'message' => 'Flight deleted successfully',
        ]);
    }
}
