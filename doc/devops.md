# Devops

Even if this project is developed by only one person there is time to same by deploying de DevOps pipeline.

## Development process

It is a TDD approach here are the development steps:

1. Before to start, check that all existing tests are passing
2. Fix the ones that fail
3. Write a test for the development to do (it fails)
4. Develop the feature until the test pass
5. Run again all the tests for non regression


## CI/CD Pipeline

Every time that some code is checked in.

* Unit tests are run
* In case of success
  * The new version is deployed
  * All tests are run
  * Dependent jobs are triggered
  * The project is deployed


## Jenkins jobs

https://jenkins2.flub78.net:8443/


### Static Analysis Job

* New item Trello_backend_Static_Analysis
* copy of Multitenant_Static_Analysis
* set the correct branch
* create a build-phing.xml
  
### Phpunit job

* New item Trello_backend_phpunit
* copy of Multitenant_phpunit

* create the vendor directory with composer update
* install the .env.testing file
* create the database
* php artisan 

## Ansible

As these tasks have to be repeated for every project it is recommended to create ansible jobs.

* to create the static analysis jenkins job
* to create a phpunit job
* to install a test/demo version on a virtual machine

Ansible environment and playbooks are managed under the projects oracle_cloud and aws_cicd.



