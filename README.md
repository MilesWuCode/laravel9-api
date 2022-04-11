# laravel9-api

## run

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

# run sail
sail up -d

# app key
sail artisan k:g

# update packages
sail composer update

# view website
open http://localhost
```
