# Text Messages Microservice

This is a microservice for sending text messages 

## Installation

### Docker

Containers are managed with docker-compose

In case you don't have it, you can find setup instructions for your OS distribution here: https://docs.docker.com/compose/install/

Run a local container using 

`$ docker-compose up --build -d`

### Laravel

You have to create `.env` file, based on `.env.example`

Generate application key

`$ docker-compose exec text_messages php artisan key:generate`

Create cache file of configs 

`$ docker-compose exec text_messages php artisan config:cache`

