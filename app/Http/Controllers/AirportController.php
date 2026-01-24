<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AirportController extends Controller
{
    public function index()
    {
        $data['airports'] = Airport::paginate(10);
        return view('admin.airport.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'code' => 'required|string|max:255|unique:airports',
            'name' => 'required|string|max:255',
            'area_code' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'code' => $request->code,
            'name' => $request->name,
            'area_code' => $request->area_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        Airport::create($data);
        session()->flash('status', 'success');
        session()->flash('message', 'Airport created successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Airport created successfully',
        ], 201);
    }

    public function update(Request $request, $airportId)
    {
        $airport = Airport::findOrFail($airportId);

        $rules = [
            'code' => 'required|string|max:255|unique:airports,code,' . $airport->id,
            'name' => 'required|string|max:255',
            'area_code' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'code' => $request->code,
            'name' => $request->name,
            'area_code' => $request->area_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        $airport->update($data);
        session()->flash('status', 'success');
        session()->flash('message', 'Airport updated successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Airport updated successfully',
        ], 200);
    }

    public function destroy($airportId)
    {
        $airport = Airport::findOrFail($airportId);
        $airport->delete();
        return redirect()->route('admin.airport.index')->with([
            'status' => 'success',
            'message' => 'Airport deleted successfully',
        ]);
    }
}
