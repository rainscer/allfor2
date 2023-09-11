1. run all dependencies (bower, composer)
2. import dump (korovo.sql) into your database
3. create .env file (template .env.example)
4. if there are some problems you can remove error database log in (app/Exceptions/Handler.php) (comment database inserts)