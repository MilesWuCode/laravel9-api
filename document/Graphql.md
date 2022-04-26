# Graphql

## nuwave/lighthouse

```sh
# install
composer require nuwave/lighthouse

# schema
php artisan vendor:publish --tag=lighthouse-schema

# IDE Support
php artisan lighthouse:ide-helper

# playground
composer require mll-lab/laravel-graphql-playground

# config
php artisan vendor:publish --tag=lighthouse-config
```

```ini
# .gitignore
_lighthouse_ide_helper.php
programmatic-types.graphql
schema-directives.graphql
```

```json
// composer.json
"scripts": {
    "post-update-cmd": [
        "@php artisan lighthouse:ide-helper"
    ],
```

```diff
# config/cors.php
return [
-   'paths' => ['api/*', 'sanctum/csrf-cookie'],
+   'paths' => ['api/*', 'graphql', 'sanctum/csrf-cookie'],
    ...
];
```
