<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use Illuminate\Http\JsonResponse;

class FileController extends Controller
{
    /**
     * Post File
     *
     * @param  \App\Http\Requests\FileRequest  $request
     * @return JsonResponse
     */
    public function file(FileRequest $request): JsonResponse
    {
        $fileName = basename($request->file('file')->store('temporary'));

        return response()->json(['file' => $fileName], 200);
    }
}
