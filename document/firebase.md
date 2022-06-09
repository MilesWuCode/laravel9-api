- install

```sh
# install
composer require kreait/laravel-firebase

#
php artisan vendor:publish --provider="Kreait\Laravel\Firebase\ServiceProvider" --tag=config
```

- Firebase Admin SDK > Generate new private key > firebase-adminsdk.json

- .env

```ini
# relative or full path to the Service Account JSON file
FIREBASE_CREDENTIALS=./firebase-adminsdk.json
# You can find the database URL for your project at
# https://console.firebase.google.com/project/_/database
FIREBASE_DATABASE_URL=https://<your-project>.firebaseio.com
```
