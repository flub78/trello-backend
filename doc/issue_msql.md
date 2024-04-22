# Issue MysQl

On Windows it happens often that the database is corrupted...

When it happens:

```
	"status": 500,
	"error": "Internal Server Error"
```

The message in the laravel.log is:
```
{"message":"SQLSTATE[HY000] [1044] Access denied for user 'boards_test_user'@'%' to database 'boards_test' (Connection: testing, SQL: select * from `boards`)"} 
```

## To fix it with phpmyadmin

1. Repay the tables from the mysql database
2. delete the user
3. flush privileges;
4. recreate the user

If I cannot re-create the user. Do it again ...
