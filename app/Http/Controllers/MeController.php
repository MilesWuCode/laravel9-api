<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $this->authorize('view', $request->user());

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
        $this->authorize('update', $request->user());

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
    public function changePassword(Request $request)
    {
        $this->authorize('update', $request->user());

        $validator = Validator::make($request->all(), [
            'old_password' => 'required|current_password',
            'new_password' => 'required|min:8|different:old_password',
            'comfirm_password' => 'required|same:new_password',
        ]);

        $validator->validate();

        //* validator rule:current_password
        // if (!Hash::check($request->old_password, $request->user()->password)) {
        //     $validator->errors()->add('old_password', 'old password wrong');

        //     return response()->json([
        //         'message' => 'old password wrong',
        //         'errors' => $validator->errors()->messages(),
        //     ], 422);
        // }

        $request->user()->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['message' => 'success'], 200);
    }
}
