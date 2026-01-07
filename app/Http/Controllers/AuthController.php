<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TenantLoginRequest;
use App\Http\Requests\TenantSignupRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(){
        return view('login');
    }

    public function signup(){
        return view('signup');
    }

    public function tenantSignup(TenantSignupRequest $request){
        try {

            $tenantValidated = $request->validated();

            if($tenantValidated){
                $tenant = User::create([
                    'name' => $tenantValidated['name'],
                    'email' => $tenantValidated['email'],
                    'password' => Hash::make($tenantValidated['password']),
                ]);
                Auth::login($tenant);
            }
            return response()->json([
                'success' => true,
                'message' => 'Signup successful'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function tenantLogin(TenantLoginRequest $request){
        try {

            $tenant = User::where('email', $request->email)->first();
            if($tenant){
                if(Hash::check($request->password, $tenant->password)){
                    Auth::login($tenant);
                    return response()->json([
                        'success' => true,
                        'message' => 'Login successful'
                    ], 200);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid password'
                    ], 401);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
