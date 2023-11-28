# LaravelAPIs
API programs with authentication 

terminal commands:

composer install 

php artisan migrate

php artisan db:seed

php artisan key:generate  =application key

php artisan passport:key   =for passport key

php artisan passport:client --personal = this is for set for client eg. put your name

php artisan serve  = run the program



Flow:

1) php artisan serve run by using this command. (http://127.0.0.1:8000/api/login)

2) Open postman put login credencial email and password, In response it generate token. (http://127.0.0.1:8000/api/user)

3) run another route, put login response token in token field now hit url. (http://127.0.0.1:8000/api/logout)
