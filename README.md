# trello-backend

Laravel backend for the Trello project

It is a REST API for boards, lists and tasks.

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
