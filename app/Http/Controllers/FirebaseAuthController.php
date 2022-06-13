<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Spatie\Fractal\Facades\Fractal;
use Illuminate\Support\Arr;

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
            ], Response::HTTP_UNAUTHORIZED);
        }

        $uid = $verifiedIdToken->claims()->get('sub');

        try {
            $firebaseUser = $auth->getUser($uid);
        } catch (UserNotFound $e) {
            return response()->json([
                'message' => 'Firebase user not found',
                'errors' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }

        $userInfo = Arr::first($firebaseUser->providerData);

        $user = User::updateOrCreate(['uid' => $uid], [
            'uid' => $uid,
            'email' => $userInfo ? $userInfo->email : $firebaseUser->email,
            'name' => $userInfo ? $userInfo->displayName : $firebaseUser->displayName,
            // 'provider' => $userInfo ? $userInfo->providerId : null,
        ]);

        if ($user->email_verified_at === null && $firebaseUser->emailVerified) {
            $user->markEmailAsVerified();
        }

        $token = $user->createToken('normal');

        $userArray = Fractal::create($user, new UserTransformer())->toArray();

        return response()->json([
            'user' => $userArray['data'],
            'token' => $token->plainTextToken,
        ], Response::HTTP_OK);
    }
}
