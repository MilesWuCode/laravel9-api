<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseAuthController extends Controller
{
    /**
     * firebase singin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function singin(Request $request)
    {
        Validator::make($request->all(), [
            'idToken' => 'required',
        ])->validate();

        $idTokenString = $request->input('idToken');

        $auth = Firebase::auth();

        try {
            $verifiedIdToken = $auth->verifyIdToken($idTokenString);
        } catch (FailedToVerifyToken $e) {
            return response()->json([
                'message' => 'The token is invalid',
                'errors' => $e->getMessage(),
            ], 401);
        }

        $uid = $verifiedIdToken->claims()->get('sub');

        // echo $uid;

        // $user = User::where('uid', $uid)->firstOrFail();
        $user = User::find(1);

        $token = $user->createToken('normal');

        return response()->json([
            'token' => $token->plainTextToken
        ], 200);
    }
}
