# NVAAK Academy

## Runtime requirements

- PHP 8.5.x (also recorded in `.php-version` and Composer's platform configuration).
- Laravel 13, Livewire 3, and Volt 1.
- Composer 2 and Node.js 22 with npm for frontend builds.
- Laravel Excel 4 / PhpSpreadsheet 5 for PHP 8.5 compatibility.

Install the locked dependencies and verify the actual PHP runtime:

```sh
php -v
composer install
composer check-platform-reqs
npm ci
npm run build
php artisan test
```

## Hostinger VPS / CloudPanel

Create a PHP site using the Laravel 13 template and select PHP 8.5. Point the
document root at the project's `public` directory. Ensure SSH/CLI PHP and any
queue workers also use PHP 8.5; changing the site's PHP setting alone does not
necessarily change CLI PHP.

For production, use `composer install --no-dev --optimize-autoloader` and verify
the installed extensions with `composer check-platform-reqs --no-dev`. Configure
the production `.env`, database, and background queue worker before launch.
Preserve the existing `APP_KEY` when updating an existing installation.

CloudPanel's static-file rules can intercept Livewire's JavaScript route and
return a 404, which prevents browser login and other interactive forms. In the
HTTPS proxy server block, route `/livewire/` to the PHP backend before the
static-file rules:

```nginx
location ^~ /livewire/ {
    proxy_pass http://127.0.0.1:8080;
    proxy_set_header Host $host;
    proxy_set_header X-Forwarded-Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
}
```

In the backend server block listening on port 8080, also route Livewire requests
through Laravel while preserving their original request URI:

```nginx
location ^~ /livewire/ {
    rewrite ^ /index.php last;
}
```

Validate with `nginx -t` before reloading Nginx. Check the Livewire script URL
rendered by `/login` and complete a browser login after deployment; a successful
direct POST to Livewire alone does not verify browser asset delivery.

The framework upgrade follows the [Laravel 13 upgrade guide](https://laravel.com/docs/13.x/upgrade).

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
