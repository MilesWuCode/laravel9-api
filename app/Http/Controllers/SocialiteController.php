<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialiteRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * 第三方用戶singin
     *
     * @param \App\Http\Requests\SocialiteRequest $request
     * @return JsonResponse
     */
    public function singin(SocialiteRequest $request): JsonResponse
    {
        $driver = $request->input('driver');

        $token = $request->input('token');

        $socialiteUser = Socialite::driver($driver)->stateless()->userFromToken($token);

        $user = User::where('email', $socialiteUser->email)->first();

        // $socialiteUser->token
        // $socialiteUser->refreshToken
        logger($socialiteUser);

        // * wip
        if ($user) {
            // 更新資料
        } else {
            // 註冊
            $user = User::create([
                'name' => $socialiteUser->name,
                'email' => $socialiteUser->email,
                'password' => '',
            ]);

            $user->markEmailAsVerified();
        }

        $token = $request->user()->createToken('normal');

        return response()->json(['token' => $token->plainTextToken], 200);
    }
}
