<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Spatie\Fractal\Facades\Fractal;

class FirebaseAuthController extends Controller
{
    /**
     * firebase register
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
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

        try {
            $firebaseUser = $auth->getUser($uid);
        } catch (UserNotFound $e) {
            return response()->json([
                'message' => 'Firebase user not found',
                'errors' => $e->getMessage(),
            ], 401);
        }

        $user = User::create([
            'email' => $firebaseUser->email,
            'name' => $firebaseUser->displayName,
            'uid' => $firebaseUser->uid,
            'email_verified_at' => $firebaseUser->emailVerified ? now() : null,
        ]);

        $token = $user->createToken('normal');

        $userArray = Fractal::create($user, new UserTransformer())->toArray();

        return response()->json([
            'user' => $userArray['data'],
            'token' => $token->plainTextToken
        ], 200);
    }

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

        $user = User::where('uid', $uid)->first();

        if (!$user) {
            try {
                $firebaseUser = $auth->getUser($uid);
            } catch (UserNotFound $e) {
                return response()->json([
                    'message' => 'Firebase user not found',
                    'errors' => $e->getMessage(),
                ], 401);
            }

            $user = User::create([
                'email' => $firebaseUser->email,
                'name' => $firebaseUser->displayName,
                'uid' => $firebaseUser->uid,
                'email_verified_at' => $firebaseUser->emailVerified ? now() : null,
            ]);
        }

        $token = $user->createToken('normal');

        $userArray = Fractal::create($user, new UserTransformer())->toArray();

        return response()->json([
            'user' => $userArray['data'],
            'token' => $token->plainTextToken
        ], 200);
    }
}
