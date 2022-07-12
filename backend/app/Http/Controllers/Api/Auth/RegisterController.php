<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegistRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(UserRegistRequest $request)
    {
        $defaultName = explode("@", $request->email);
       
        User::create([
            'name' =>  $defaultName[0],//初期値はemailの@マークより左側を入れたい
            'email' =>  $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json(['created' => true], Response::HTTP_OK);
    }
}