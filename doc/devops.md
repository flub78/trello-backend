# Devops

Even if this project is developped by only one person there is time to same by deploying de DevOps pipeline.

## Development process

It is a TDD approach here are the development steps:

1. Before to start, check that all existing tests are passing
2. Fix the ones that fail
3. Write a test for the development to do (it fails)
4. Develop the feature until the test pass
5. Run again all the tests for non regression


## CI/CD Pipeline

Everytime that some code is checked in.

* Unit tests are run
* In case of success
  * The new version is deployed
  * All tests are run
  * Dependent jobs are triggered
  * The project is deployed


