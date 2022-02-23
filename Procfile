web: vendor/bin/heroku-php-nginx public/
{
    if (\App::environment('production')) {
        \URL::forceScheme('https');
    }
}