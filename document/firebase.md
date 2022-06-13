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

## custom guard (wip)

- change users table

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('id')->change();
    $table->dropColumn('email_verified_at');
    $table->dropColumn('password');
    $table->dropColumn('remember_token');
});
```

- app/Models/User.php

```php
protected $primaryKey = 'id';
public $incrementing = false;
protected $keyType = 'string';
```

- app/Providers/AuthServiceProvider.php

```php
use App\Models\User;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Laravel\Firebase\Facades\Firebase;

class AuthServiceProvider extends ServiceProvider
{
  // ...
  public function boot()
  {
      $this->registerPolicies();

      // * firebase
      Auth::viaRequest('firebase', function (Request $request) {
          $token = $request->bearerToken();

          $auth = Firebase::auth();

          try {
              $verifiedIdToken = $auth->verifyIdToken($token);
          } catch (FailedToVerifyToken $e) {
              return null;
          }

          $uid = $verifiedIdToken->claims()->get('sub');

          return User::find($uid);
      });
  }
}
```

- config/auth.php

```php
'guards' => [
    'api' => [
        'driver' => 'firebase',
    ],
],
```

- routes/api.php

```php
Route::middleware('auth:api')
```
