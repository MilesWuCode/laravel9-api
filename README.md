# laravel9-api

## run

```sh
# composer install
docker run --rm -v $(pwd):/app composer install --ignore-platform-reqs

# env file
cp .env.sail

# run sail
sail up -d

# app key
sail artisan k:g

# update packages
sail composer update

# open bowser
http://localhost
```
