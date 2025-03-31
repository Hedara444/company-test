<?php


namespace App\Services;



use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthServices
{
    public function register(array $data): JsonResponse
    {

    User::create([
         'name' => $data['name'],
         'email' => $data['email'],
         'password' => bcrypt($data['password'])
     ]);
     return response()->json([
         'message'=> "User Created",
     ], 201);
 }

    public function login(array $credentials): JsonResponse
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
         return response()->json([
                'message' => ['The provided credentials are incorrect.'],
            ],422);
        }


        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'token' =>$token,
        ]);
    }

    public function logout(User $user): JsonResponse
    {
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
