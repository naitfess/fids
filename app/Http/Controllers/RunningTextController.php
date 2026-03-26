<?php

namespace App\Http\Controllers;

use App\Models\RunningText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RunningTextController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $data['runningTexts'] = RunningText::when($search, function ($query) use ($search) {
                $query->where('text', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.running-text.index', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        RunningText::create([
            'text' => $request->text,
        ]);

        session()->flash('status', 'success');
        session()->flash('message', 'Running text created successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Running text created successfully',
        ], 201);
    }

    public function update(Request $request, $runningTextId)
    {
        $runningText = RunningText::findOrFail($runningTextId);

        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $runningText->update([
            'text' => $request->text,
        ]);

        session()->flash('status', 'success');
        session()->flash('message', 'Running text updated successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Running text updated successfully',
        ], 200);
    }

    public function destroy($runningTextId)
    {
        $runningText = RunningText::findOrFail($runningTextId);
        $runningText->delete();

        return redirect()->route('admin.running-text.index')->with([
            'status' => 'success',
            'message' => 'Running text deleted successfully',
        ]);
    }
}
