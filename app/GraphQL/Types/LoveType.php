<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Models\Post;

class LoveType
{
    public function getLikeCount(Post $post, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): string
    {
        return $post->like_count;
    }

    public function getDislikeCount(Post $post, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): string
    {
        return $post->dislike_count;
    }
}
