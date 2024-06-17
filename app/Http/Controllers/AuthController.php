<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = Admin::where('email', $request->email)->first();
        $password = $request->password;
        if (!$user or !Hash::check($password, $user->password)) {
            return ResponseController::error('Email or Password incorrect', 401);
        }
        $token = $user->createToken('admin')->plainTextToken;
        return ResponseController::data([
            'token' => $token
        ]);
    }
    public function update(AdminUpdateRequest $request, Admin $user)
    {
        $data = [
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
        ];

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if($user->update($data)){
            return ResponseController::success('Successfully updated', 200);
        }

        return $user;
    }
    public function getme()
    {
        $user = auth()->user();
        return response()->json([
            'user' => $user
        ]);
    }
    public function logOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ResponseController::success('You have successfully logged out', 200);
    }
}
