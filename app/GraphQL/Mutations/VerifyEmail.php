<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\Events\Verified;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class VerifyEmail
{
    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array{}  $args The field arguments passed by the client.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Shared between all fields.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Metadata for advanced query resolution.
     * @return mixed
     */
    public function __invoke($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = User::where('email', $args['email'])->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            throw new GraphqlException('Your email address is verified.');
        }

        $count = $user->verifies()
            ->where('code', $args['code'])
            ->where('expires', '>=', now())
            ->count();

        if ($count !== 1) {
            throw new GraphqlException('Unauthorized');
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return true;
    }
}
