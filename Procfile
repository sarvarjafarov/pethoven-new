release: php artisan storage:link --force && php artisan migrate --force && php artisan db:seed --class=LunarSetupSeeder --force
web: vendor/bin/heroku-php-apache2 public/
