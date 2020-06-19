# Laravel Instruction
Back to [README.md](../README.md)

## Install

You can install the package via composer:

* Add this code in your `composer.json` in `repositories` section
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

* Run require command 
```bash
composer require k.kostoglodov/apiator
```
* Add service provider in `./config/app.php`
```php
return [
    // ...
    'providers' => [
        // ...
        /** custom service providers */
        Patrikap\Apiator\ApiatorServiceProvider::class,
    ]
];
```

* Add the middleware in `app/Http/Kernel.php`:
```php
class Kernel extends HttpKernel
{
    // ... some properties
    protected $routeMiddleware = [
        // ... some middleware
        'apiator'         => \Patrikap\Apiator\Middleware\ApiatorMiddleware::class,
    ];

    // ... some properties
    protected $middlewarePriority = [
        'apiator', // recommend putting the first item
        // ... some middleware
    ];
}
```

* you can publish the config file with (optionally):
```bash
php artisan vendor:publish --provider="Patrikap\Apiator\ApiatorServiceProvider" --tag="config" 
```

## Usage

This package provides middleware that can be added to your api routes in `routes/api.php`.

```php
Route::post('/endpoint', function () {})->middleware('apiator');
```

### Back to [README.md](../README.md)
