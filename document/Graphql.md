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
php artisan vendor:publish --tag=graphql-playground-config

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

## mll-lab/graphql-php-scalars

```sh
composer require mll-lab/graphql-php-scalars
```

[schema.graphql](../graphql/schema.graphql)

```gql
# graphql/schema.graphql
# mll-lab/graphql-php-scalars
scalar Email @scalar(class: "MLL\\GraphQLScalars\\Email")
scalar JSON @scalar(class: "MLL\\GraphQLScalars\\JSON")
```
