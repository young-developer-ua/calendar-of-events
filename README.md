
# Getting started

## Installation

### Install all the dependencies using composer

    composer install

### Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

### Create Docker for database .env file

`cp MysqlDocker/devops/local/.env.example MysqlDocker/devops/local/.env`

### Start the local development database

    docker-compose up

### Generate a new application key

    php artisan key:generate

### Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

### Database seeding

Run the database seeder and you're done

    php artisan db:seed

### Start the local development server

    php artisan serve

### You can now access the server at http://localhost:8000
