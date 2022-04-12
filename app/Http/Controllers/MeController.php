<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeUpdateRequest;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Fractal\Facades\Fractal;

class MeController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        // return $request->user();
        return Fractal::create($request->user(), new UserTransformer())
            ->respond();
    }

    /**
     * Undocumented function
     *
     * @param MeUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MeUpdateRequest $request): JsonResponse
    {
        // * example
        // dump($request->validated());
        // dump($request->safe()->only(['name']));
        // dump($request->safe()->except(['other']));
        // dump($request->safe()->all());

        $request->user()->update($request->validated());

        return Fractal::create($request->user(), new UserTransformer())
            ->respond();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
            'comfirm_password' => 'required|same:password',
        ])->validate();

        $password = Hash::make($validator['password']);

        $request->user()->update(['password' => $password]);

        return Fractal::create($request->user(), new UserTransformer())
            ->respond();
    }
}
