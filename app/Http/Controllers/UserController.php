<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $data['users'] = User::paginate(10);
        return view('admin.user.index', $data);
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Password::defaults()],
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('logo')) {
            $logoPath = Storage::disk('public_uploads')->put('logos', $request->file('logo'));
        }

        $data = [
            'name' => $request->name,
            'role' => 'staff',
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'logo' => $logoPath,
        ];

        User::create($data);
        session()->flash('status', 'success');
        session()->flash('message', 'User created successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
        ], 201);
    }

    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            // 'password' => ['nullable', Password::defaults()],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('logo')) {
            if ($user->logo && Storage::disk('public_uploads')->exists($user->logo)) {
                Storage::disk('public_uploads')->delete($user->logo);
            }
            $logoPath = Storage::disk('public_uploads')->put('logos', $request->file('logo'));
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'logo' => $logoPath ?? $user->logo,
        ];

        $user->update($data);

        session()->flash('status', 'success');
        session()->flash('message', 'User updated successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
        ], 200);
    }

    public function destroy($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->logo && Storage::disk('public_uploads')->exists($user->logo)) {
            Storage::disk('public_uploads')->delete($user->logo);
        }

        $user->delete();
        return redirect()->route('admin.user.index')->with([
            'status' => 'success',
            'message' => 'User deleted successfully',
        ]);
    }
}
