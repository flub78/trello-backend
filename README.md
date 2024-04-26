# trello-backend

Laravel backend for the Trello project

It is a REST API for boards, lists and tasks.

But it is mainly a workbench to experiment on full stack WEB development.

The idea is to experiment on REST APIs served by Laravel using MySql and to have clients developed in React.

## Starting a development server

in development:

    XAMPP
        Start Apache with PHP 8 support
        Start MySql

    cd trello-backend
    php artisan serve

The project is available at http://127.0.0.1:8000/

It is then possible to register new users and login. 

(Todo: check how to disable registration.)

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

copy the database credentials into the .env file
and migrate the database

    php artisan migrate

### composer update

### Setup the authentication

    composer require laravel/breeze --dev
    php artisan breeze:install
        react

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
    php artisan make:migration create_lists_table
    php artisan make:migration create_tasks_table

    php artisan make:migration create_task_comments_table
    php artisan make:migration create_checklists_table
    php artisan make:migration create_tag_colors_table
    php artisan make:migration create_tags_table
    php artisan make:migration create_checklist_items_table


it creates the file app/database/migrations/2024_03_02_174125_create_boards_table.php


    php artisan migrate
	php artisan migrate:rollback --step=1


    php artisan make:model Board

    ...\app\Models\Board.php] created successfully.


    php artisan make:controller api/BoardController

    ...\app\Http\Controllers\api\BoardController.php] created successfully.


## REST API

    http://127.0.0.1:8000/api/boards

## Testing

    php artisan test --filter=Api	
 	php artisan test --testsuite=Unit
	php artisan test --testsuite=Feature

    php artisan test --stop-on-failure --filter=TagModel

	php artisan test  tests\Unit\BoardModelTest.php
	php artisan test  tests\Unit\ChecklistItemModelTest.php
	php artisan test  tests\Unit\ChecklistModelTest.php

	php artisan test  tests\Unit\ColumnModelTest.php
	php artisan test  tests\Unit\ExampleTest.php
	php artisan test  tests\Unit\SchemaModelTest.php		delete the tasks ...       

    