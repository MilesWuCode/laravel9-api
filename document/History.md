# history

## email verification

-   app/Http/Controllers/AuthController.php
-   app/Notifications/CustomVerifyEmail.php
-   app/Models/User.php

```php
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail

public function sendEmailVerificationNotification()
public function verifies(): HasMany
```

## sanctum

```sh
# install
composer require laravel/sanctum

# migration
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# run migrate
php artisan migrate
```

-   app/Http/Kernel.php

```diff
'api' => [
-   // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
+   \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

## barryvdh/laravel-ide-helper

```sh
# install
composer require barryvdh/laravel-ide-helper

# generate
php artisan ide-helper:generate

# model
php artisan ide-helper:models
```

```ini
# .gitignore
_ide_helper.php
```

## spatie/laravel-fractal

```sh
# install
composer require spatie/laravel-fractal

# config
php artisan vendor:publish --provider="Spatie\Fractal\FractalServiceProvider"

# make
php artisan make:transformer UserTransformer
```

## beyondcode/laravel-query-detector

```sh
# install
composer require beyondcode/laravel-query-detector

# provider
php artisan vendor:publish --provider="BeyondCode\QueryDetector\QueryDetectorServiceProvider"
```

```diff
# .env
QUERY_DETECTOR_ENABLED=true
```

## sentry/sentry-laravel

```sh
composer require sentry/sentry-laravel
```

```diff
# App/Exceptions/Handler.php
public function register()
{
    $this->reportable(function (Throwable $e) {
+       if (app()->bound('sentry')) {
+           app('sentry')->captureException($e);
+       }
    });
}
```

```sh
# env
php artisan sentry:publish --dsn=xxx

# test
php artisan sentry:test
```

## spatie/laravel-medialibrary

```sh
# install
composer require spatie/laravel-medialibrary

# migration
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"

# migrate
php artisan migrate

# config
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="config"
```

```php
# config/filesystems.php
'disks' => [
    'media' => [
        'driver' => 'local',
        'root'   => public_path('media'),
        'url'    => env('APP_URL').'/media',
    ],
],
```

```ini
# .env
# medialibrary
MEDIA_DISK=media
```

```diff
# public/media/.gitignore
*
!.gitignore
```

```php
# app/Models/User.php
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use InteractsWithMedia;
```

## spatie/laravel-tags

```sh
# install
composer require spatie/laravel-tags

# migration
php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="tags-migrations"

# migrate
php artisan migrate

# config
php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="tags-config"
```

```php
# app/Models/Post.php
use Spatie\Tags\HasTags;

class Post extends Model
{
    use HasTags;
}
```

## beyondcode/laravel-comments

```sh
# install
composer require beyondcode/laravel-comments

# migrations
php artisan vendor:publish --provider="BeyondCode\Comments\CommentsServiceProvider" --tag="migrations"

# migrate
php artisan migrate

# config
php artisan vendor:publish --provider="BeyondCode\Comments\CommentsServiceProvider" --tag="config"
```

```php
// config/comments.php
'comment_class' => \App\Models\Comment::class,

// app/Models/User.php
// Auto Approve Comments
use BeyondCode\Comments\Contracts\Commentator;

class User extends Authenticatable implements Commentator
{
    /**
     * Check if a comment for a specific model needs to be approved.
     * @param mixed $model
     * @return bool
     */
    public function needsCommentApproval($model): bool
    {
        return false;
    }
}

// app/Models/Post.php
use BeyondCode\Comments\Traits\HasComments;

class Post extends Model
{
    use HasComments;
    ...
}
```

## spatie/laravel-comments

```sh
composer require spatie/laravel-comments
```

## cybercog/laravel-love

```sh
# install
composer require cybercog/laravel-love

# migrate
php artisan migrate

# default: like, dislike
php artisan love:reaction-type-add --default

# set model reacterable

# migration file
php artisan love:setup-reacterable --model="App\Models\User" --nullable

# migrate
php artisan migrate

# create love_reacters table data
php artisan love:register-reacters --model="App\Models\User"

# set model reactable

# migration file
php artisan love:setup-reactable --model="App\Models\Post" --nullable

# migrate
php artisan migrate

# create love_reactants table data
php artisan love:register-reactants --model="App\Models\Post"
```

### set model

```php
// set model reacterable
use Cog\Contracts\Love\Reacterable\Models\Reacterable as ReacterableInterface;
use Cog\Laravel\Love\Reacterable\Models\Traits\Reacterable;

class User extends Authenticatable implements ReacterableInterface
{
    use Reacterable;

// set model reactable
use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableInterface;
use Cog\Laravel\Love\Reactable\Models\Traits\Reactable;

class Post extends Model implements HasMedia, ReactableInterface
{
    use Reactable;
```

## laravel/socialite

```sh
composer require laravel/socialite
```

```php
# config/services.php
# api不使用redirect
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => 'http://localhost/auth/callback',
],

'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => 'http://localhost/auth/callback',
],
```

```ini
# .env
# socialite
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
```

## file upload to temporary

```php
# config/filesystems.php
'disks' => [
    // file upload to temporary
    'temporary' => [
        'driver' => 'local',
        'root'   => storage_path('app/temporary'),
    ],
],
```

## test

```sh
# env
cp .env.sail .env.testing

# sqlite
touch database/database.sqlite
```

```diff
# .env.testing
- DB_*
```

```xml
<!-- phpunit.xml -->

<!-- enabled -->
<env name="DB_CONNECTION" value="sqlite"/>
```

```php
// tests/TestCase.php
// * setUp
public function setUp(): void
{
    parent::setUp();

    Artisan::call('db:seed --class=TestSeeder');
    ...
}
```

```sh
# test
php artisan test
php artisan test --filter UserTest

# parallel
php artisan test --parallel
php artisan test --parallel --recreate-databases
```

## sail

```sh
# install
curl -s "https://laravel.build/project-name" | bash

# run
./vendor/bin/sail up -d

# alias
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'

# with
php artisan sail:install --with=mysql,redis,mailhog

# publish
sail artisan sail:publish
```

## old version

```sh
composer create-project laravel/laravel="8.*.*" project-name
```
