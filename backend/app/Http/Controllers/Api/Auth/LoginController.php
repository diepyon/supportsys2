<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;

use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use \Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(Operator $operator, Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $operator = Operator::whereEmail($request->email)->first();
            $operator->tokens()->delete();
            $token = $operator->createToken("login:operator{$operator->id}")->plainTextToken;
            return response()->json(['token' => $token , 'operator'=>$operator], Response::HTTP_OK);
        }
        return response()->json('User Not Found.', Response::HTTP_INTERNAL_SERVER_ERROR);
    } 
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out'], 200);
    }
}