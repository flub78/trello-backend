# trello-backend

Laravel backend for the Trello project

It is a REST API for boards, lists and tasks.

## REST API

## Database schema

## Deployment

## Development steps

- Create the github project
- Create a local Laravel project
- Test the local Laravel server
- Merge the Laravel and Github projects to check it in
- Create the database
- Implement the REST API
- Add authentication

### Creating a Laravel Project

    composer create-project laravel/laravel lara-trello
    cd lara-trello
    php artisan serve

Warning, the PHP and composer version need to be compatible with the Laravel version.

### Clone the github project

git clone https://github.com/flub78/trello-backend.git

copy the Laravel project into the github project, except the README.md.

### Create a database

database boards
user xxx
password yyy

copy the database credentiel into the .env file
and migrate the database

php artisan migrate

### Setup the authentication

    composer require laravel/breeze --dev
    php artisan breeze:install

## Design questions

- Should I add a GUI to manage users and generate authentication tokens ?
