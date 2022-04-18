```sh
# model
php artisan make:model Post --all --api --test

# factory
php artisan make:factory PostFactory -m Post

# policy
sail php artisan make:policy PostPolicy -m Post

# request
php artisan make:request PostCreateRequest
php artisan make:request PostUpdateRequest
```
