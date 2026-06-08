<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
 public function login (LoginRequest $request): JsonResponse
 {
  $user = User::where('email', $request->email)->first();
  if (!$user || ! Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'The provided credentials are incorrect.'], 401);
    }

    $user->tokens()->delete();

    $token = $user->createToken('auth_token')-> plainTextToken;

    return response()->json([
        'success' => true,
        'data' => [
            'user' => $user,
            'access_token' => $token,
        ],
        'message' => 'Successfully logged in.'
    ]);

 }

public function me (Request $request): JsonResponse
{
    return response()->json([
        'success'=> true,
        'data'=> $request->user(),
        'message'=> 'User profile retrieved succesfully.'
        ]);


}
public function logout (Request $request): JsonResponse

{
$request->user()->currentAccessToken()->delete();

return response()->json([
    'success'=> true,
    'message'=> 'Successfully logged out.'
    ]);
}

}
