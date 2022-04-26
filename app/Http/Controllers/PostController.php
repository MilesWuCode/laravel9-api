<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostFileAddRequest;
use App\Http\Requests\PostFileDelRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\LikeRequest;
use App\Http\Requests\ListRequest;
use App\Models\Post;
use App\Transformers\PostTransformer;
use App\Transformers\CommentTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\Fractal\Facades\Fractal;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class);
    }

    /**
     * 列表
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'page' => 'sometimes|numeric|min:1',
            'limit' => 'sometimes|numeric|min:1|max:100',
            'sort' => 'sometimes|in:id_asc,id_desc,updated_at_asc,updated_at_desc',
        ])->validate();

        $page = $request->input('page', 1);
        $limit = $request->input('limit', 5);
        $sort = $request->input('sort', 'id_asc');

        [$column, $order] = preg_split('/_(?=(asc|desc)$)/', $sort);

        $posts = $request->user()
            ->posts()
            ->with(['tags', 'loveReactant.reactions', 'loveReactant.reactionCounters'])
            ->orderBy($column, $order)
            ->paginate($limit, ['*'], 'page', $page);

        return Fractal::create($posts, new PostTransformer())
            // * 手動includes
            // ->parseIncludes('tag')
            // ->includeTags()
            ->respond();
    }

    /**
     * 新增
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $request->user()->posts()->create($request->all());

        if ($request->has('tag')) {
            $post->setTag($request->input('tag') ?? []);
        }

        $post->setFile('gallery', $request->input('gallery') ?? []);

        return Fractal::create($post, new PostTransformer())
            // * 手動includes
            // ->parseIncludes('tag')
            // ->includeTags()
            ->respond();
    }

    /**
     * 顯示
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post): JsonResponse
    {
        return Fractal::create($post, new PostTransformer())
            // * 手動includes
            // ->parseIncludes('tag')
            // ->includeTags()
            ->respond();
    }

    /**
     * 更新
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $post->update($request->all());

        if ($request->has('tag')) {
            $post->setTag($request->input('tag') ?? []);
        }

        return Fractal::create($post, new PostTransformer())
            // * 手動includes
            // ->parseIncludes('tag')
            // ->includeTags()
            ->respond();
    }

    /**
     * 刪除
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post): Response
    {
        return response($post->delete(), 200);
    }

    /**
     * 設定喜歡或不喜歡
     *
     * @param LikeRequest $request
     * @param \App\Models\Post  $post
     * @return JsonResponse
     */
    public function like(LikeRequest $request, Post $post): JsonResponse
    {
        // ? like_count,dislike_count數字不同步問題
        // TODO:修改.env的QUEUE_CONNECTION=sync才會同步
        // TODO:思考該不該顯示數字

        // $post = Post::with([
        //     'loveReactant.reactions',
        // ])->find($id);

        $request->user()
            ->setLike($post, $request->input('type', ''));

        return Fractal::create($post->fresh(), new PostTransformer())
            ->respond();
    }

    /**
     * 檔案新增
     *
     * @param PostFileAddRequest $request
     * @param \App\Models\Post  $post
     * @return JsonResponse
     */
    public function fileAdd(PostFileAddRequest $request, Post $post): JsonResponse
    {
        $this->authorize('update', $post);

        $post->setFile($request->input('collection'), [$request->input('file')]);

        return Fractal::create($post, new PostTransformer())
            ->parseIncludes($request->input('collection'))
            ->respond();
    }

    /**
     * 檔案刪除
     *
     * @param PostFileDelRequest $request
     * @param \App\Models\Post  $post
     * @return JsonResponse
     */
    public function fileDel(PostFileDelRequest $request, Post $post): JsonResponse
    {
        $this->authorize('update', $post);

        // 配合 PostFileDelRequest 檢查 media_id 是否存在於資料表
        // $mediaItems = $post->getMedia($request->input('collection'));

        // $mediaItem = $mediaItems->find($request->input('media_id'));

        // if($mediaItem){
        //     return response()->json(['message' => 'done']);
        // }else{
        //     return response()->json(['message' => 'media not found.'], 404);
        // }

        // 補捉model錯誤訊息
        try {
            $post->delFile($request->input('collection'), $request->input('media_id'));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }

        return response()->json(['message' => 'done']);
    }

    /**
     * 評論列表
     *
     * @param \App\Http\Requests\ListRequest $request
     * @param \App\Models\Post  $post
     * @return JsonResponse
     */
    public function comment(ListRequest $request, Post $post): JsonResponse
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 5);
        $sort = $request->input('sort', 'id_asc');

        [$column, $order] = preg_split('/_(?=(asc|desc)$)/', $sort);

        $comments = $post->comments()
            ->approved()
            ->orderBy($column, $order)
            ->paginate($limit, ['*'], 'page', $page);

        return Fractal::create($comments, new CommentTransformer())
            ->respond();
    }

    /**
     * 評論新增
     *
     * @param \App\Http\Requests\StoreCommentRequest $request
     * @param \App\Models\Post  $post
     * @return JsonResponse
     */
    public function storeComment(StoreCommentRequest $request, Post $post): JsonResponse
    {
        $comment = $post->comment($request->input('comment'));

        return Fractal::create($comment, new CommentTransformer())
            ->respond();
    }
}
