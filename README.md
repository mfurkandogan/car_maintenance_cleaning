In this project , Customers will be able to log in, order, and track services that they bought over the application.


## Step 1: Create database 

First you need to create a table called "maintenance". 

Default settings : 

DB_DATABASE=maintenance
<br>
DB_USERNAME=root
<br>
DB_PASSWORD=

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

Here is the code you need to run to upload the cars informations to redis (this command is set to run hourly from the moment the project is up and running):

```
$ php artisan cars:get
```
## Step 4: Final Step
Finally, run the command via composer:

```
php artisan serve
```

## User Endpoints

### Post - Login Request : /api/v1/login	

### Get - User Request : /api/v1/user

### Post - Register Request : /api/v1/register

### Post - Logout Request : /api/v1/logout


## Operation Endpoints

### Post - Add Balance Request : /api/v1/addBalance

### Post - Create Order Request : /api/v1/createOrder

### Get - Get Order Request : /api/v1/getOrders

### Get - List Cars Request : /api/v1/getCars

### Get - List Services Request : /api/v1/getServices
