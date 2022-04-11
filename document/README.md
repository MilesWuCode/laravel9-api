# history

## email verification

-   app/Models/User.php

```diff
- class User extends Authenticatable
+ class User extends Authenticatable implements MustVerifyEmail
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
