# REST APIs

This project implement a REST API server on several resources

The server is a backend for a Trello clone

The resources are
* boards
* columns
* tasks
* task_comments
* tag_colors
* tags
* checlists
* checklist_items

The following HTTP verbs are supported GET, POST, PATCH and DELETE

## To get all the resources

    http://127.0.0.1:8000/api/tasks

Pagination, filtering and sorting are supported


### Sorting

    http://127.0.0.1:8000/api/tasks?sort=-created_at
	http://127.0.0.1:8000/api/tasks?sort=-name
	http://127.0.0.1:8000/api/tasks?sort=description

### Filtering

	http://127.0.0.1:8000/api/tasks?filter=name:%3Etask%202
	http://127.0.0.1:8000/api/tasks?filter=name:%3Etask%202&filter=name:<=Task 5
	http://127.0.0.1:8000/api/tasks?filter=name:%3Etask%202&filter=name:%3C=Task%205
	
	http://127.0.0.1:8000/api/tasks?filter=created_at:>2024-03-21T09:23:46.000000Z
	
	http://127.0.0.1:8000/api/tasks?filter=created_at:>2024-03-21T09:23:46.000000Z&filter=created_at:<=2024-03-21T09:23:49.000000Z

### Pagination

    http://127.0.0.1:8000/api/tasks?per_page=2
    http://127.0.0.1:8000/api/tasks?per_page=2&page=3

Note

    per_page parameter is not included in the links returned by the json server. There is a per_page key in the response which must be added to the links.

    I assume that it is to allow the client to change this value at any time ...

    When the per page parameter is changed, the concep of next page does not mean anything, the navigation should be reset to the first page.

## To get one resource

    http://127.0.0.1:8000/api/tasks/2

## To create an element

    POST on http://127.0.0.1:8000/api/tasks/

## To update it

    PATCH on http://127.0.0.1:8000/api/tasks/2




