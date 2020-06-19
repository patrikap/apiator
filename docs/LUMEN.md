# Lumen Instruction
Back to [README.md](../README.md)

## Install

You can install the package via composer:

### Add this code in your `composer.json` in `repositories` section
```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@gitlab.cdek.ru:k.kostoglodov/apiator.git",
      "branch": "master"
    }
  ]
}
```

### Run require command 
```bash
composer require k.kostoglodov/apiator
```

### Edit your `./bootstrap/app.php`

The **first way** - if you want to intercept all requests, then do this:
```php
/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
*/
$app->middleware([
    // ... some providers
    Patrikap\Apiator\Middleware\ApiatorMiddleware::class,
]);
```
The **second way** - if you want to intercept only specific routes and groups of routes, then do this:
```php
/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
*/
$app->routeMiddleware([
    // ... some providers
    'apiator' => \Patrikap\Apiator\Middleware\ApiatorMiddleware::class,
]);
```

### Register ServiceProvider

```php
/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
*/
// ... some providers
$app->register(Patrikap\Apiator\ApiatorServiceProvider::class);
```

### Config customization

If you want to change the settings of the service, you must copy the settings file manually to the working directory and make the following changes to `./bootstrap/app.php`

Step by step:
* Make config dir:
```bash
mkdir ./config
```
* Copy config file:
```bash
cp ./vendor/k.kostoglodov/apiator/config/apiator.php ./config/apiator.php
```
* Change `./bootstrap/app.php`
 ```php
/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
 */
 // ... some config
 $app->configure('apiator');
 ```

## Usage
If you chose the **first way**, then you don’t need to contribute anything, the middleware will work on all routes automatically.

If you have chosen the **second way**, then simply add a middleware to your routes `./routes/web.php`:
```php
$router->group([
    'prefix'     => 'some_prefix',
    // ... some params
    'middleware' => ['apiator'],
], static function () use ($router) {});
```

### Back to [README.md](../README.md)
