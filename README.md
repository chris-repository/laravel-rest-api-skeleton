# laravel-rest-api-skeleton

This is an skeleton application for a rest api built in laravel.

There is currently an example item resource accesible by `/example-items`.

### Core packages on top of Laravel used

* Doctrine 2
* Laravel Doctrine (with migrations)
* Fractal

## Installation

`composer install`

Copy and edit .env.example

`cp .env.example .env`

## Running on docker (port 8000)

First install docker-compose: https://docs.docker.com/compose/install/

Then run `docker-compose up` or `docker-compose up -d` for a background process

You'll need to share permissions with docker for it to create files in the repo

`chmod 777 storage/ & chmod 777 storage/logs & chmod 777 bootstrap/cache/`

## Testing

`phpunit`