```sh
# install
composer require laravel/sanctum

# migration
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# run migrate
php artisan migrate
```

- app/Http/Kernel.php

```diff
'api' => [
-   // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
+   \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

- .env

```ini
# 前端頁面使用/sanctum/csrf-cookie
# sanctum.config的stateful參數有設
SANCTUM_STATEFUL_DOMAINS=domain
```

- config/cors.php

```php
// axios.defaults.withCredentials = true;
'supports_credentials' => true,
```
