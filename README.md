# SD Client

A Laravel package for use with the [sdlogin](https://github.com/curio-team/sdlogin) OpenID connect server. Now using curio.codes!

## Installation

__‼__ Please make sure your app is using _https_, to prevent unwanted exposure of token, secrets, etc.

To use sdclient in your project:

1. In your laravel project run: `composer require curio/sdclient`

2. Set these keys in your .env file:

    * `SD_CLIENT_ID`
    * `SD_CLIENT_SECRET`
    * `SD_API_LOG` *(optional)*
        * _Default:_ `no`
        * Set to `yes` to make SdClient log all usage of access_tokens and refresh_tokens to the default log-channel.
    * `SD_APP_FOR` *(optional)*
        * _Default:_ `teachers`
        * This key determines if students can login to your application.
        * May be one of:
    * `all`: everyone can login, you may restrict access using guards or middleware.
    * `teachers`: a student will be completely blocked and no user will be created when they try to login.
    * `SD_USE_MIGRATION` *(optional)*
        * _Default:_ `yes`
        * Set to no if you want to use your own migration instead of the users migration this package provides
    * `SD_SSL_VERIFYPEER` *(optional)*
        * _Default:_ `yes`
        * Set to `no` if you want to disable SSL verification. This is only recommended for during development and only on trusted networks.

3. Alter your User model and add the line: `public $incrementing = false;`

4. _(Recommended)_ Remove any default users-migration from your app, because SdClient will conflict with it. Do _not_ remove the user-model. If you want to keep using your own migration, in your .env file set: `SD_USE_MIGRATION=no`

5. Lastly, run `php artisan migrate`.

## Usage

### Logging in

Redirect your users to `http://yoursite/sdclient/redirect`, this will send your user to _amologin_ for authentication.

You should have a named route that will serve your users with a button or direct redirect to `/sdclient/redirect.`

Example;

```
Route::get('/login', function(){
 return redirect('/sdclient/redirect');
})->name('login');

```

### Catch the after-login redirect

After a succesfull login, SdClient will redirect you to `/sdclient/ready`. You may define a route in your applications `routes/web.php` file to handle this.

Example;

```
Route::get('/sdclient/ready', function(){
 return redirect('/educations');
})
```

### Logging out

Send your user to `/sdclient/logout`.
_Please note:_ a real logout cannot be accomplished at this time. If you log-out of your app, but are still logged-in to the _amologin_-server, this will have no effect.

### Laravel's `make:auth`

Don't use this in combination with SdClient.

## SdApi

Apart from being the central login-server, _login.amo.rocks_ also exposes an api. Please note this api is currently undocumented, although there are options to explore the api:

* Refer to _amologin_'s [routes/api.php](https://github.com/Curio/amologin/blob/master/routes/api.php) file.
* Play around at [apitest.amo.rocks](https://apitest.amo.rocks/).

### SdClient API Interface

An example of calling the api through SdClient;

```
namespace App\Http\Controllers;
use \Curio\SdClient\Facades\SdApi;

class MyController extends Controller
{
 //This method is protected by the auth-middleware
 public function index()
 {
   $users = SdApi::get('users');
   return view('users.index')->with(compact('users'));
 }
}

```

**Known 'bug':** Currently the SdApi class doesn't check if the token expired but just refreshes it anytime you use it.

### `SdApi::get($endpoint)`

* Performs an HTTP-request like `GET https://api.amo.rocks/$endpoint`.
* This method relies on a user being authenticated through the sdclient first. Please do call this method only from routes and/or controllers protected by the _auth_ middlware.
* Returns a Laravel-collection

## Contributing

1. Clone this repository to your device
2. Inside the root of this repository run `composer install`
3. Create a test project in which you will use this package (Follow [Usage](#usage) instructions above)
4. Add the package locally using the following additions to your composer.json:
 ```json
  "repositories": [
   {
    "type": "path",
    "url": "../sdclient"
   }
  ],
 ```

	* __Note:__ `../sdclient` should point to where you cloned this package
5. Run `composer require "curio/sdclient @dev"` inside the test project

You can now test and modify this package. Changes will immediately be reflected in the test project.
