<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(Request $request)
    {
        return $this->authRepository->login($request->all());
    }

    public function register(Request $request)
    {
        return $this->authRepository->register($request->all());
    }

    public function logout()
    {
        return $this->authRepository->logout();
    }

    public function refresh()
    {
        return $this->authRepository->refresh();
    }

    public function user()
    {
        return $this->authRepository->user();
    }
}
