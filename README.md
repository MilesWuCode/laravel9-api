# laravel9-api

## sail run

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

# option
sail php artisan sentry:publish --dsn=xxx

# view website
open http://localhost
```
