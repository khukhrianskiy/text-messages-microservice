# Text Messages Microservice

This is a microservice for sending text messages 

## Installation

### Docker

Containers are managed with docker-compose

In case you don't have it, you can find setup instructions for your OS distribution here: https://docs.docker.com/compose/install/

Make sure port `3306` is free

Run a local container using 

`$ docker-compose up --build -d`

### Laravel

Create `.env` file, based on `.env.example`

Open console 

`$ docker-compose exec text_messages bash`

Install dependencies 

`$ composer install`

Generate application key

`$ php artisan key:generate`

Regenerate cache 

`$ php artisan config:cache`


### Testing 

Create `test.sqlite` file in `TextMessages` directory

Run `$ php artisan test` to be sure everything is working correctly

