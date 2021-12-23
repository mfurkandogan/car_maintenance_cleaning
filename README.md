In this project , Customers will be able to log in, order, and track services that they bought over the application.


## Step 1: Create database 

First you need to create a table called "maintenance"

## Step 2: Step up a database tables

Run this command to add the tables to the database

```
$ php artisan migrate
```

## Step 3: Step up a database seeders

Run this command to insert sample data into tables

```
$ php artisan db:seed
```
## Step 4: Upload vehicle information to Redis

Here is the code you need to run to upload the cars informations to redis:

```
$ php artisan cars:get
```

Finally, run the command via composer:

```
php artisan serve
```
