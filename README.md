
## Intro 

Just Simple Personal Project



## Installation

- create .env file( sample include in repo)
- create midtrans account
- include midtrans credetial in .env for example:
    - SERVER_KEY =Sserver-rfoEAZy_
    - IS_PRODUCTION =false
    - IS_SANITIZED =true
    - IS_3DS =true
    - MIDTRANS_URL=https://app.cc

- create mysql database and include in .env
- run "composer install"
- run "composer dump-autoload"
- run "npm install"
- run "php artisan migrate:fresh"
- run "php artisan test" --> to run test
- run "php artisan db:seed"
- run "npm run dev"
- run "php artisan serve"



### Description
Rest api base app using MVC pattern, admin pannel was created using filament (/admin)\
Created Using laravel 10 and php 8.1

