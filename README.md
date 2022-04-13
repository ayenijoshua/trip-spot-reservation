<p align="center"><h1>Trip Spots Reserations API</h1></p>

## About

This a simple spot reservations api, where user can reserve and cancle reservations on a trip
You can find the routes under routes/api directory.
Please NOTE that this project is not considering authentication/authorization

## Set Up

This project use the laravel framework [documentation](https://laravel.com/docs) for development 

- Make sure you have php(>=8.0) installed on your system
- Clone this repository
- Run [php artisan test --env=testing], to run the integration tests
- Run php artisan migrate to run the db migrations
- Run php artisan db:seed to seed your database tables
- Run php artisan serve, to serve the application.

## Endpoints

## All Trips
```bash
    curl --location --request GET 'http://localhost/api/trips' \
    --header 'Content-Type: application/json' \
```
## Create Trips
```bash
    curl --location --request POST 'http://localhost/api/trips' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "name": "Trip to the moon",
        "allocated_slots":10
    }'
```
## Update Trips
```bash
    curl --location --request PUT 'http://localhost/api/trips/{id}' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "name": "Trip to the Sun",
        "allocated_slots":10
    }'
```
## Trip reservations
```bash
    curl --location --request GET 'http://localhost/api/trips/{id}/reservations' \
    --header 'Content-Type: application/json' \
```
## Total Trip reservations
```bash
    curl --location --request GET 'http://localhost/api/trips/{id}/total-reservations' \
    --header 'Content-Type: application/json' \
```
# Delete Trips
```bash
    curl --location --request DELETE 'http://localhost/api/trips/{id}' \
    --header 'Content-Type: application/json' \
```
## All Users
```bash
    curl --location --request GET 'http://localhost/api/users' \
    --header 'Content-Type: application/json' \
```
## Create User
```bash
    curl --location --request POST 'http://localhost/api/users' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "name": "James",
    }'
```
## Update User
```bash
    curl --location --request PUT 'http://localhost/api/users/{id}' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "name": "Josh",
    }'
```
## User reservations
```bash
    curl --location --request GET 'http://localhost/api/users/{id}/reservations' \
    --header 'Content-Type: application/json' \
```
## Total User reservations
```bash
    curl --location --request GET 'http://localhost/api/users/{id}/total-reservations' \
    --header 'Content-Type: application/json' \
```
## Delete Users
```
    curl --location --request DELETE 'http://localhost/api/users/{id}' \
    --header 'Content-Type: application/json' \
``

## All Reservations
```bash
    curl --location --request GET 'http://localhost/api/reservations' \
    --header 'Content-Type: application/json' \
```
## Create Reservations
```bash
    curl --location --request POST 'http://localhost/api/reservations' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "trip_id": 1,
        "user_id":1,
        "slots":2
    }'
```

## Cancle Reservation
```bash
    curl --location --request PUT 'http://localhost/api/reservations/canclation' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "trip_id": 1,
        "user_id":1,
        "slots":2
    }'
```
## Delete Reservations
```bash
    curl --location --request DELETE 'http://localhost/api/reservations/{id}' \
    --header 'Content-Type: application/json' \
```


