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
public function verifyCode(string $code): bool
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

class Pos extends Model
{
    use HasTags;
}
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

<!-- enabled:memory or disabled:file -->
<env name="DB_DATABASE" value=":memory:"/>
```

```sh
# test all
sail php artisan test --parallel --processes=4

# filter
sail php artisan test --filter UserTest
```
