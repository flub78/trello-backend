# Testing

REST API server tests:

* Unit tests for the model
  * CRUD tests
* Feature test for the controllers
  *  CRUD through the REST API
  *  Incorrect requests
  *  filtering
  *  ordering
  *  pagination
  *  resource not found
* Authentication tests

## Test context

A specific database boards_test is used for testing.

A test entry named testing is added into config.database.php

The .env.testing file is set. 

    APP_ENV=testing
    DB_CONNECTION=testing

    DB_TEST_PORT=3306
    DB_TEST_DATABASE=boards_testDB_CONNECTION=testing

    DB_TEST_USERNAME=boards_test_user
    DB_TEST_PASSWORD=...


Then the database can be migrated

    php artisan migrate --seed --env=testing

## Test execution

```
  php artisan test
  php artisan test --stop-on-failure
  php artisan test --testsuite=Unit
  php artisan test --testsuite=Feature

  php artisan test --filter=BoardModel
  php artisan test --filter=TagColorModel
  php artisan test --filter=BoardAPiController

  php artisan test  tests\Unit\BoardModelTest.php
  php artisan test  tests\Unit\ChecklistItemModelTest.php
  php artisan test  tests\Unit\ChecklistModelTest.php

```