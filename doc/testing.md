# Testing

As this is mainly a REST API server We hae the following tests:

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

### Note

It has been observed that the Feature tests for authentication were breaking the database.

At some point I will need a same mode to reset the database before every test. As it will slow test down it should remain an option that can be disabled.

## Test execution

```
  php artisan test
  php artisan test --testsuite=Unit
  php artisan test --testsuite=Feature

  php artisan test --filter=BoardModel
  php artisan test --filter=TagColorModel
  php artisan test --filter=BoardAPiController
```