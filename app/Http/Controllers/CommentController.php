<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\LikeRequest;
use App\Transformers\CommentTransformer;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Fractal\Facades\Fractal;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Comment $comment): JsonResponse
    {
        return Fractal::create($comment, new CommentTransformer())
            ->respond();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $comment->update($request->all());

        return Fractal::create($comment, new CommentTransformer())
            ->respond();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        return response($comment->delete(), 200);
    }

    /**
     * 設定喜歡或不喜歡
     *
     * @param \App\Http\Requests\LikeRequest $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(LikeRequest $request, Comment $comment): JsonResponse
    {
        $request->user()
            ->setLike($comment, $request->input('type', ''));

        return Fractal::create($comment->fresh(), new CommentTransformer())
            ->respond();
    }
}
