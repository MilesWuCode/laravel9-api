# history

## email verification

-   app/Http/Controllers/AuthController.php
-   app/Notifications/CustomVerifyEmail.php
-   app/Models/User.php

```diff
- class User extends Authenticatable
+ class User extends Authenticatable implements MustVerifyEmail

+ public function sendEmailVerificationNotification()
+ public function verifies(): HasMany
+ public function verifyCode(string $code): bool
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
