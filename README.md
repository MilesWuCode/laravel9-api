# laravel9-api

- https://github.com/MilesWuCode/laravel9-api

## sail

```sh
# composer install
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs

# env file
cp .env.sail

# alias sail
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'

# run sail
sail up -d

# app key
sail artisan k:g

# update packages
sail composer update

# update autoload
sail composer dump-autoload

# migrate
sail php artisan migrate

# option
# sentry
sail php artisan sentry:publish --dsn=xxx

# website
open http://localhost

# mailhog
open http://localhost:8025
```

## test

```sh
# test
sail php artisan test
sail php artisan test --filter UserTest

# parallel
sail php artisan test --parallel
sail php artisan test --parallel --recreate-databases
```
