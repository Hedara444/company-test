<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthServices;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use App\Services\PhoneVerificationService;

class AuthController extends Controller
{

    protected $phoneVerificationService;

    public function construct(PhoneVerificationService $phoneVerificationService)
    {
        $this->phoneVerificationService = $phoneVerificationService;
    }

   public function __construct(private readonly AuthServices $authService) {

    }

    public function register(RegisterRequest $request): JsonResponse
    {
       return $this->authService->register($request->validated());
    }

    public function login(LoginRequest $request):JsonResponse
    {

        return $this->authService->login($request->validated());
    }

    public function verify(Request $request):JsonResponse
    {
        $request->validate([
            'phone' => 'required|regex:/^\+\d{1,3}-\d{1,4}\d{1,4}\d{1,4}$/',
        ]);

        $result = $this->phoneVerificationService->verifyPhoneNumber($request->phone);

        return response()->json($result);
    }
    public function logout(Request $request): JsonResponse
    {
     $user = $request->user();
     $this->authService->logout($user);

        return response()->json(['message'=>'Logged out successfully']);
    }
}
