<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

News aggregator API written with Laravel 11:

- Fetches articles from NewsAPI, The Guardian and The New York Times.
- Full text search using elastic search
- Laravel Sanctum API authentication
- Redis cache
- Login to app `docker-compose exec app /bin/sh` then run the schduler `php artisan schedule:work` to fetch new articles from the above mentioned providers.
- [API Documentation](https://documenter.getpostman.com/view/396935/2sAYXEFdvb)

## License

The Laravel framework and this application are open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
