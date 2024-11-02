#!/usr/bin/python
# -*- coding:utf8 -*

# Databases to check
databases = ["mysql", "information_schema", "nonexistent_db", "multi"]
user = "root"
password = ""
# URLs to check
urls = [
    "http://localhost/index.php",
    "http://localhost/phpmyadmin"
]