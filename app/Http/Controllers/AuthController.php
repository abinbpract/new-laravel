<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        // $this->middleware('auth:sanctum')->only(['user', 'logout']);
        $this->authRepository = $authRepository;
    }

    public function register(RegisterUserRequest $request)
    {
        return $this->authRepository->register($request);
    }

    public function login(LoginUserRequest $request)
    {
        return $this->authRepository->login($request);
    }

    public function logout(Request $request)
    {
        return $this->authRepository->logout($request->user());
    }
    public function user(Request $request)
    {
        return $this->authRepository->user($request);
    }
}
