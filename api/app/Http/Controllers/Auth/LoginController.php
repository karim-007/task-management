<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/login",
     *   tags={"User login"},
     *   summary="Login",
     *   operationId="login",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *      *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function authentication(Request $request)
    {
        //print_r($request->all());
        Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:6'
        ])->validate();

        if (!auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['status' => 'fail', 'message' => 'Incorrect Email or Password!'], 403);
        }
        $user = auth()->user();
        $accessToken = $user->createToken('authToken')->plainTextToken;
        $refreshToken = $user->createToken('refreshToken')->plainTextToken;


        $abality[] = [];
        $abality[] = [
            'action' => 'read',
            'subject' => 'Admin',
        ];
        $abality[] = [
            'action' => 'read',
            'subject' => 'Dashboard',
        ];
        $user = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'ability' => $abality,
        ];
        if (isset($user)) {
            return response()->json(['status' => 'success', 'user' => $user, 'access_token' => $accessToken, 'refresh_token' => $refreshToken], 200);
        }
        return response()->json(['status' => 'fail', 'message' => 'Invalid Request'], 404);
    }

}
