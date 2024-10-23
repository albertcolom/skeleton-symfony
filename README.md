## Requirements:
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Docker compose:
- `php:8.3-fpm-alpine` with `composer:latest`
- `nginx:alpine3.20-slim`
- `mariadb:11.5.2`
- `rabbitmq:4.0.2-management-alpine`
- `redis:7.4.1-alpine`
- `swaggerapi/swagger-ui:v5.17.14`
- `fluent/fluent-bit:3.1`
- `docker.elastic.co/elasticsearch/elasticsearch:8.15.2`
- `docker.elastic.co/kibana/kibana:8.15.2`
- `wurstmeister/kafka:2.13-2.8.1`
- `zookeeper:3.9.2`

## The Environment:
- **API:** http://localhost:8000
- **API Documentation:** http://localhost:8001
- **ElasticSearch:** http://localhost:9200
- **Kibana:** http://localhost:5601
- **RabbidMQ:** http://localhost:15672 user: `guest` password: `guest`
- **MariaDB:** host: `localhost` port: `3306` user: `root` password: `root`
- **Redis:** host: `localhost` port: `6379`
- **Kafka** host: `localhost` port: `9092`

## Workflow
![Workflow](https://i.imgur.com/xxKP36u.jpeg)


### Available make commands:
You can view this info by running `make` or `make help`
```sh
build                Build/Rebuild project
cc                   Clear the cache. Optional parameter "env", default "dev". Example: make cc env=prod
composer-install     Composer install
composer-update      Composer update
console              Symfony console, Optional parameter "command". Example: make console command=debug:autowiring
consume-events       Consume events from kafka
event-log            Tail event log. Optional parameter "env", default "dev". Example: make event-log env=prod
help                 Show help
phpcs                Run phpcs PSR12
phpstan              Run phpstan level 6
purge-queues         Purge rabbitmq queues
purge-test-topic     Delete messages test topic
purge-topic          Delete messages topic
rebuild-container    Rebuild containers
rebuild-db           Rebuild Mysql. Optional parameter "env", default "dev". Example: make rebuild-db env=test
rebuild-es           Rebuild ElasticSearch data. Optional parameter "env", default "dev". Example: make rebuild-db env=test
redis-flush          Flush redis
restart              Restart containers
shell                Interactive shell inside docker
status               Show containers status
stop                 Stop containers
test-acceptance      Run unit testing
test-unit            Run unit testing
up                   Start containers
```

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
$ bin/console debug:router
```
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

## Buses:
We have 4 different types of bus implemented with `Symfony Messenger`
- Synchronous
  - CommandBus: `public function dispatch(Command $command): void`
  - QueryBus: `public function ask(Query $query): Response`
  - CacheQueryBus: `public function ask(Query $query, int $ttl = self::TTL_HOUR): Response`
- Asynchronous
  - EventBus: `public function publish(DomainEvent ...$domainEvents): void`

## Process async events:
Command to consume `RabbidMQ` messages from `ampqp` transport
```sh
$ make consume-events
```
We can find 3 listeners listening in charge of clearing the cache and updating the projection in the reading model:
- `OnFooWasCreated`
- `OnFooWasUpdated`
- `OnFooWasRemoved`

## Domain event:
Example of a serialized domain event when a Foo was created
```json
{
  "payload": {
    "foo_id": "7cc900eb-663a-4292-876d-5a77eeefade1",
    "name": "Some foo name",
    "created_at": "2022-03-06 17:03:25",
    "occurred_on": "2022-03-06 17:03:25"
  },
  "metadata": {
    "id": "53a4fabbb9ca462a872321814eee61ce",
    "name": "app.context.foo.domain.event.foo_was_created"
  }
}
```
### Domain event logs
**Monolog**  

We have a middleware to log domain events.  
You can access the logs at the following path: `/var/log/domain_event_{env}.log`
```sh
$ make event-log
```

**Logstash**  

We use the `logstash` to parse the logs and publish them to `elasticsearch` with the index `domain-event-%{env}-%{+YYYY.MM.dd}`  
You can view the logs using `kibana` http://localhost:5601

## Testing
Unit testing `PHPUnit`
```sh
$ make test-unit
```
Acceptance test `Behat`
```sh
$ make test-acceptance
```

## Static Analysis Tool
PHPStan `level 6`
```sh
$ make phpstan
```
PHP Code Sniffer `PSR 12`
```sh
$ make phpcs
```

## Screenshots
**Api Documentation:**
![API Documentation](https://i.imgur.com/CjABGJi.jpeg)

**Domain events log**
![Domain events log](https://i.imgur.com/CKiSkSm.jpg)

**ElasticSearch**
![ElasticSearch](https://i.imgur.com/iM8sbjy.png)

**RabbitdMQ**
![RabbitMQ](https://i.imgur.com/m8teRa4.png)
