# trello-backend

Laravel backend for the Trello project

It is a REST API for boards, lists and tasks.

## Execution

in development:

    XAMPP
        Start Apache with PHP 8 support
        Start MySql

    cd lara-trello
    php artisan serve

The project is available at http://127.0.0.1:8000/

It is then possible to register new users and login. 

(Todo chack how to disable registration.)

## Development steps

- Create the github project (no files)
- Create a local Laravel project
- Test the local Laravel server
- Merge the Laravel and Github projects to check it in (instructions on the Github site.)
- Create the database
- Implement the REST API
- Add authentication

### Creating a Laravel Project

    composer create-project laravel/laravel lara-trello


Warning, the PHP and composer version need to be compatible with the Laravel version.

### Clone the github project

See the github instructions on the new Github project to declare it as remote source.

Commit the project into Github.

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

### To disable registration

Remove from Auth file

    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

Remove the controller in app/Http/Controllers/Auth/RegisteredUserController.php

Remove the view resources/views/auth/register.blade.php.



## REST API

/boards should return something like

```json
{
    "boards": [
        {
            "id:": "webapp",
            "name": "WEBAPP",
            "description": "Workspace Flub78",
            "favorite": true,
            "recent": true,
            "href": "board/webapp",
            "image": "code_editor.jpg",
            "theme": "dark"
        },
        {
            "id": "marly",
            "name": "Marly",
            "description": "",
            "favorite": false,
            "recent": false,
            "href": "board/marly",
            "image": "IMG_20181118_152709.jpg",
            "theme": "dark"
        }
    ]
}
```

/lists?board=webapp     should return something like
```json
{
    [
        {"name": "todo", "description": "Thing to do"},
        {"name": "done", "description": "completed tasks"}
    ]
}
```

/tasks
/tasks?board=webapp
/tasks?board=webapp&list=todo


## Database schema

````
boards
    id
    name
    background_color
    background_url

lists
    id=todo
    board="webapp"

tasks
    id
    board
    list
    title
    description
    image
    creation_date
    due_date
    estimate

checklists
    id
    task

check_items
    id
    checklist
    description
    checked

color_tags
    id
    name
    color

set_color_tags
    id
    task
    color_tag

````

## Deployment

## Design questions

- Should I add a GUI to manage users and generate authentication tokens ?

## Create the REST API server

    php artisan make:migration create_boards_table

it creates the file app/database/migrations/2024_03_02_174125_create_boards_table.php


    php artisan migrate

    php artisan make:model Board

    ...\app\Models\Board.php] created successfully.


    php artisan make:controller api/BoardController

    ...\app\Http\Controllers\api\BoardController.php] created successfully.