### Requirements:
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Docker compose:
- php:8.0.8-fpm-alpine
- nginx:1.20-alpine
- mysql:8.0
- rabbitmq:3.8-management-alpine
- redis:6.2.5-alpine
- swaggerapi/swagger-ui
- docker.elastic.co/elasticsearch/elasticsearch:7.1.1
- docker.elastic.co/logstash/logstash:7.1.1
- docker.elastic.co/kibana/kibana:7.1.1

### The Environment:
- **API:** http://localhost:8000
- **API Documentation:** http://localhost:8001
- **ElasticSearch:** http://localhost:9200
- **Kibana:** http://localhost:5601
- **RabbidMQ:** http://localhost:15672 user: `guest` password: `guest`
- **MySQL:** host: `localhost` port: `3306` user: `root` password: `root`
- **Redis:** host: `localhost` port: `6379`

## Installation:
Clone this repository
```sh
$ git clone git@github.com:albertcolom/skeleton-symfony.git
```
Build the project
```sh
$ make build
```
Start environment
```sh
$ make up
```

## Routes:
```sh
 ---------------- -------- -------- ------ --------------------------
  Name             Method   Scheme   Host   Path
 ---------------- -------- -------- ------ --------------------------
  get_v1_foo       GET      ANY      ANY    /v1/foo/{fooId}
  get_v1_all_foo   GET      ANY      ANY    /v1/foo
  put_v1_foo       PUT      ANY      ANY    /v1/foo/{fooId}
  delete_v1_foo    DELETE   ANY      ANY    /v1/foo/{fooId}
  post_v1_foo      POST     ANY      ANY    /v1/foo
 ---------------- -------- -------- ------ --------------------------
```

## Testing

Unit testing `PHPUnit`
```sh
$ make test-unit
```
Acceptance test `Behat`
```sh
$ make test-acceptance
```

### Static Analysis Tool

PHPStan `level 5`
```sh
$ make phpstan
```
PHP Code Sniffer `PSR 12`
```sh
$ make phpcs
```
