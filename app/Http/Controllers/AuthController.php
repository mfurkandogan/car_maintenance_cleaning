<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            return response([
                    'message' => 'An error occurred while register',
                    'errors' => $validation->errors()->toArray(),
                    'status' => Response::HTTP_NOT_ACCEPTABLE]
            );
        }

        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);

    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials',
                'status' => Response::HTTP_UNAUTHORIZED
            ]);
        }
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        return response([
            'message' => 'Login token= ' . $token,
            'status' => Response::HTTP_OK
        ]);
    }

    public function user()
    {
        return new UserResource(Auth::user());
    }

    public function logout()
    {
        if (auth()->user()->tokens()->delete()) {
            return response([
                'message' => 'Logout successful',
                'status' => Response::HTTP_OK
            ]);
        }
        return response([
            'message' => 'Logout failed',
            'status' => Response::HTTP_NOT_ACCEPTABLE
        ]);
    }
}
