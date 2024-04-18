# Backend localization

As the backend returns some strings, mainly error messages it is logical to localize the error messages.

    https://laravel.com/docs/11.x/localization


## Setup

    php artisan lang:publish

The default language is sotred in config/app.php (en by default)

To set the locale:

    use Illuminate\Support\Facades\App;

    if (! in_array($locale, ['en', 'fr'])) {
        abort(400);
    }

    App::setLocale($locale);

The french translation can be found here https://github.com/s-damian/laravel-lang-fr

The idea is to add a lang parameter to the API.


