# Motorway backend project

## Application setup

```
docker-compose build
docker-compose up -d
docker-compose exec motorway composer install
docker-compose exec motorway php bin/phpunit tests
```

## Application demo

1. Setup the app loading some fixtures:

`http://127.0.0.1:8080/setup`

2. Simulate a motorway entry:

`http://127.0.0.1:8080/enter/ROUTE-A14/STATION-AN/DEVICE-1`

3. Simulate a motorway exit:

`http://127.0.0.1:8080/enter/STATION-MI/{log-id}`

where `log-id` is the result of previous call

4. Knowing user monthly due:

`http://127.0.0.1:8080/user/USER-1`

## Resources

* [The database schema](https://github.com/francescotrucchia/motorway-exercise/blob/master/app/data/schema.sql)
* [The integration test with a whole application flow](https://github.com/francescotrucchia/motorway-exercise/blob/master/app/tests/Integration/MotorwayTest.php)
* [The api to store logs](https://github.com/francescotrucchia/motorway-exercise/blob/master/app/public/index.php)