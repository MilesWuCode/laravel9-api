<?php

namespace App\Transformers;

use App\Models\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'tag', 'gallery',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Post $post): array
    {
        return [
            'id' => (int) $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'status' => (boolean) $post->status,
            'like' => $post->like,
            'like_count' => (int) $post->like_count,
            'dislike_count' => (int) $post->dislike_count,
            'publish_at' => $post->publish_at?->format('Y-m-d'),
            'created_at' => $post->created_at->format('Y-m-d H:i:s'),
            'updated_at' => (string) $post->updated_at,
        ];
    }

    public function includeTag(Post $post)
    {
        return $this->collection($post->tags, new TagTransformer);
    }

    public function includeGallery(Post $post)
    {
        return $this->collection($post->getMedia('gallery'), new MediaTransformer);
    }
}
