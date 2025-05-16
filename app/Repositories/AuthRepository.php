<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\Nominee;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * Register a new user.
     */
    public function register($request)
    {
        try {
            $user = DB::transaction(function () use ($request) {
                $user = User::create([
                    'username' => $request->first_name . ' ' . $request->last_name,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                UserProfile::create([
                    'user_id'               => $user->id,
                    'first_name'            => $request->first_name,
                    'last_name'             => $request->last_name,
                    'address_line1'         => $request->address_line1,
                    'address_line2'         => $request->address_line2,
                    'city'                  => $request->city,
                    'state'                 => $request->state,
                    'country'               => $request->country,
                    'country_code'          => $request->country_code,
                    'mobile_number'         => $request->mobile_number,
                    'alternative_number'    => $request->alternative_number,
                    'national_id_number'    => $request->national_id_number,
                    'national_id_type'      => $request->national_id_type,
                    'national_id_copy_path' => $request->national_id_copy_path,
                    'date_of_birth'         => Carbon::createFromFormat('d-m-Y', $request->date_of_birth)->format('Y-m-d'),
                ]);

                // Create nominee
                Nominee::create([
                    'user_id'        => $user->id,
                    'email'          => $request->nominee_email,
                    'nominee_name'   => $request->nominee_name,
                    'relationship'   => $request->relationship,
                    'contact_number' => $request->nominee_contact_number,
                ]);
                return $user;
            });

            $token = $user->createToken('register')->plainTextToken;

            return response()->json(['message' => 'User registered successfully', 'user' => $user, 'token' => $token,], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User registration failed', 'error' => $e->getMessage(), 'line' => $e->getLine()], 500);
        }
    }

    /**
     * Login the user and issue a token.
     */
    public function login($request)
    {
        try {

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $user->tokens()->delete();
            $token = $user->createToken('login')->plainTextToken;

            return response()->json(['message' => 'User logged in successfully', 'token' => $token,], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User login failed', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Logout the user.
     */
    public function logout($user)
    {
        try {
            $user->tokens()->delete();
            return response()->json(['message' => 'User logged out successfully',], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Logout failed', 'error' => $e->getMessage(),], 500);
        }
    }
    public function user($request)
    {
        try {
            $user = Auth::user();
            return response()->json(['message' => 'User found', 'user' => $user,], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User not found', 'error' => $e->getMessage(),], 500);
        }
    }
}
