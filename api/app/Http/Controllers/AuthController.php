<?php

namespace App\Http\Controllers;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * * @OA\Get(
     *   path="/api/user",
     *   summary="login user information with permission",
     *   tags={"Auth"},
     *   @OA\Response(
     *     response=200,
     *     description="user info"
     *   )
     * )
     */
    public function user(): \Illuminate\Http\JsonResponse
    {
        $user = \auth()->user();
        $abality = [];

        $abality[] = [
            'action' => 'read',
            'subject' => 'Auth',
        ];
        $abality[] = [
            'action' => 'read',
            'subject' => 'Dashboard',
        ];
        $user = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'ability' => $abality,
        ];

        return response()->json($user,200);
    }


    public function allUsers(Request $request)
    {
        $users = User::where('id','!=',auth()->id())->get();
        return new UserCollection($users);
    }
}
