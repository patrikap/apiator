# Apiator

Service designed to help when working with REST API:
* converts API responses to a single format
* logs requests and responses (without delaying the response to the client)

Because logging called after a response sent to the client, this work is very fast.

## Docs links

* [Laravel](./docs/LARAVEL.md)
* [Lumen](./docs/LUMEN.md)

## To be continue...
* add logging implementation
* separation into interfaces ("request logger" and "response logger")
* add request and response logging formatters
* correct finding version response
* add some loggers
    * in file
    * in db
    * in LogStash
* add tests

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Authors
* Developers 
    * [Patrikap](https://github.com/patrikap)
* Mentors and code reviewers
    * [Victor Fursenko](https://github.com/va-fursenko) 
    * [Roman Kievsky](https://github.com/rkievsky)

## Acknowledgments
* [REST API Best Practices](https://habr.com/ru/post/351890/)
* [Laravel REST API Response Builder](https://laravel-news.com/laravel-rest-api-response-builder)
* [How to Build and Test a RESTful API](https://www.toptal.com/laravel/restful-laravel-api-tutorial)
* Inspiration

## Licence
MIT, please see [LICENCE](LICENSE) for more information.
