#!/usr/bin/python
# -*- coding:utf8 -*

user = "root"
password = ""

# URLs to check
urls = [
    "http://localhost",
    "http://localhost/phpmyadmin"
]

# Databases to check
# databases = ["information_schema", "boards", "boards_test"]
databases = {
    "information_schema": {
        "name": "information_schema"
    },
    "boards": {
        "name": "boards", 
        "user": "boards_user",
        "password": "boards_raven_197!"
    },
    "boards_test": {
        "name": "boards_test",
        "user": "boards_test_user", 
        "password": "boards_test_197!"
    }
}

