<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserRegisterRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function login(Request $request){
        $user = User::where('email', $request->email)->first();
        $password = $request->password;
        if(!$user OR !Hash::check($password,$user->password)){
            return ResponseController::error('Email or Password incorrect',401);
        }
        $token = $user->createToken('user')->plainTextToken;
        return ResponseController::data([
            'token' => $token
        ]);
    }
    public function register(UserRegisterRequest $request){
        $email = $request->email;
        $user = User::where('email',$email)->first();
        User::create([
            'name'=>$request->name,
            'email'=>$email,
            'password'=>Hash::make($request->password),
        ]);
        return ResponseController::success('Successfully created',201);
    }
    public function update(UserUpdateRequest $request,User $user){
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
        return ResponseController::error('Something went wrong',400);
    }
    public function getme(){
        $user = auth()->user();
        return response()->json([
            'user' => $user
        ]);
    }
    public function logOut(Request $request){
        $request->user()->currentAccessToken()->delete();
        return ResponseController::success('You have successfully logged out',200);
    }
}
