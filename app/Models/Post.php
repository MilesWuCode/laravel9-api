<?php

namespace App\Models;

use BeyondCode\Comments\Traits\HasComments;
// use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableInterface;
// use Cog\Laravel\Love\Reactable\Models\Traits\Reactable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

/**
 * App\Models\Post
 *
 * @method static \Database\Factories\PostFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @mixin \Eloquent
 * @property-read \App\Models\User|null $user
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $body
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $publish_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\BeyondCode\Comments\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $gallery
 * @property-read int|null $gallery_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property \Illuminate\Database\Eloquent\Collection|\Spatie\Tags\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post wherePublishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withAnyTagsOfAnyType($tags)
 */
class Post extends Model implements HasMedia
{
    use HasTags;
    use HasComments;
    use HasFactory;
    use InteractsWithMedia;
    // use Reactable;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'status',
        'publish_at',
    ];

    protected $attributes = [
        'publish_at' => null,
    ];

    protected $casts = [
        'publish_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        /**
         * ->useDisk('s3')
         * ->singleFile()
         * ->onlyKeepLatest(3)
         * ->withResponsiveImages()
         */

        // use Spatie\MediaLibrary\MediaCollections\File;

        $this->addMediaCollection('gallery')
        // ->acceptsFile(function (File $file) {
        //     return $file->mimeType === 'image/jpeg';
        // })
            ->acceptsMimeTypes(['image/jpeg'])
            ->registerMediaConversions(function (Media $media) {
                /**
             * ->border(10, 'black', Manipulations::BORDER_OVERLAY)
             * ->crop('crop-center', 400, 400)
             * ->greyscale()
             * ->quality(80)
             * ->sharpen(10)
             */

                $this
                    ->addMediaConversion('thumb')
                    ->width(320)
                    ->height(240);
            });
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(320)
            ->height(240)
            ->performOnCollections('gallery');
    }

    public function gallery(): MorphMany
    {
        return $this->morphMany(config('media-library.media_model'), 'model')
            ->where('collection_name', 'gallery');
    }

    public function setTag(array $tag = []): void
    {
        $this->syncTagsWithType($tag, 'post');
    }

    public function setFile(string $collection, array $files = []): void
    {
        foreach ($files as $file) {
            if (Storage::disk('temporary')->exists($file)) {
                $this->addMediaFromDisk($file, 'temporary')->toMediaCollection($collection);
            }
        }
    }

    public function delFile(string $collection, int $media_id): void
    {
        $mediaItems = $this->getMedia($collection);

        $mediaItem = $mediaItems->find($media_id);

        if ($mediaItem) {
            $mediaItem->delete();
        } else {
            throw new \Exception('media not found.');
        }
    }

    // public function getLikeCountAttribute(): int
    // {
    //     // list n+1: ->with(['tags', 'loveReactant.reactionCounters', 'loveReactant.reactionTotal'])
    //     return $this->viaLoveReactant()
    //         ->getReactionCounterOfType('Like')
    //         ->getCount();
    // }

    // public function getDislikeCountAttribute(): int
    // {
    //     // list n+1: ->with(['tags', 'loveReactant.reactionCounters', 'loveReactant.reactionTotal'])
    //     return $this->viaLoveReactant()
    //         ->getReactionCounterOfType('Dislike')
    //         ->getCount();
    // }

    // public function getLikeAttribute(): string
    // {
    //     // list n+1: ->with(['loveReactant.reactions'])
    //     if (Auth::check() && $this->viaLoveReactant()->isReactedBy(Auth::user(), 'Like')) {
    //         return 'Like';
    //     } elseif (Auth::check() && $this->viaLoveReactant()->isReactedBy(Auth::user(), 'Dislike')) {
    //         return 'Dislike';
    //     }

    //     return '';
    // }
}
