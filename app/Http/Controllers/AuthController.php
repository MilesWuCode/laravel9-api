<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * User register.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'comfirm_password' => 'required|same:password',
        ])->validate();

        $input = $request->all();

        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);

        if (!$user->hasVerifiedEmail()) {
            event(new Registered($user));
        }

        return response()->json($user->toArray(), 200);
    }

    /**
     * send verify email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendVerifyEmail(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
        ])->validate();

        $email = $request->email;

        $user = User::where('email', $email)->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            abort(400, 'Email already verified.');
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'success'], 200);
    }

    /**
     * verify email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required',
        ])->validate();

        $email = $request->email;

        $user = User::where('email', $email)->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            abort(403, 'Your email address is verified.');
        }

        if (!$user->verifyCode((string) $request->code)) {
            abort(401, 'Unauthorized');
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return response()->json(['message' => 'success'], 200);
    }

    /**
     * Login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function login(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ])->validate();

        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $token = $request->user()->createToken('normal');

            return ['token' => $token->plainTextToken];
        } else {
            abort(401, 'Unauthorized');
        }
    }

    /**
     * Logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ['message' => 'success'];
    }
}
