ifeq ($(env),)
    ENVIRONMENT := dev
else
    ENVIRONMENT := $(env)
endif

.PHONY: help
help: Makefile ## Show help
	@echo ""
	@echo "\033[1mAvailable commands:\033[0m"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'
	@echo ""

.PHONY: build
build: stop rebuild-container composer-install rebuild-db purge-queues redis-flush  ## Build/Rebuild project

.PHONY: up
up: ## Start containers
	@docker-compose up -d

.PHONY: rebuild-container
rebuild-container: ## Rebuild containers
	@docker-compose up --build --force-recreate --no-deps -d

.PHONY: status
status: ## Show containers status
	@docker-compose ps

.PHONY: stop
stop: ## Stop containers
	@docker-compose stop

.PHONY: console
console: ## Symfony console, Optional parameter "command". Example: make console command=debug:autowiring
	@docker-compose exec php-fpm env XDEBUG_MODE=off bin/console ${command}

.PHONY: cc
cc: ## Clear the cache. Optional parameter "env", default "dev". Example: make cc env=prod
	@docker-compose exec php-fpm rm -rf var/cache/${ENVIRONMENT}/*
	@docker-compose exec php-fpm env XDEBUG_MODE=off php bin/console cache:warmup -e "${ENVIRONMENT}" --quiet
	@docker-compose exec redis redis-cli FLUSHALL

.PHONY: shell
shell: ## Interactive shell inside docker
	@docker-compose exec php-fpm sh

.PHONY: composer-install
composer-install: ## Composer install
	@docker-compose exec php-fpm env XDEBUG_MODE=off composer install

.PHONY: composer-update
composer-update: ## Composer update
	@docker-compose exec php-fpm env XDEBUG_MODE=off composer update

.PHONY: purge-queues
purge-queues: ## Purge rabbitmq queues
	@docker-compose exec rabbitmq rabbitmqctl stop_app
	@docker-compose exec rabbitmq rabbitmqctl reset
	@docker-compose exec rabbitmq rabbitmqctl start_app

.PHONY: redis-flush
redis-flush: ## Flush redis
	@docker-compose exec redis redis-cli FLUSHALL

.PHONY: consume-domain-events
consume-domain-events: ## Consume domain events from rabbitmq
	@docker-compose exec php-fpm env XDEBUG_MODE=off php bin/console messenger:consume ampqp

.PHONY: phpstan
phpstan: ## Run phpstan level 5
	@docker-compose exec php-fpm env XDEBUG_MODE=off php -d memory_limit=4G vendor/bin/phpstan analyse -c phpstan.neon

.PHONY: phpcs
phpcs: ## Run phpcs PSR12
	@docker-compose exec php-fpm env XDEBUG_MODE=off php vendor/bin/phpcs --report=code --colors --extensions=php --standard=PSR12 -p src

.PHONY: test-unit
test-unit: ## Run unit testing
	@docker-compose exec php-fpm env XDEBUG_MODE=off php vendor/bin/phpunit

.PHONY: test-acceptance
test-acceptance: ## Run unit testing
	@docker-compose exec php-fpm env XDEBUG_MODE=off TEST_TOKEN=_`date +%d%m%y%H%M%S`_`echo $$RANDOM` php vendor/bin/behat

.PHONY: event-log
event-log: ## Tail event log. Optional parameter "env", default "dev". Example: make event-log env=prod
	@docker-compose exec php-fpm tail -f var/log/domain_event_"${ENVIRONMENT}".log

.PHONY: rebuild-db
rebuild-db: ## Rebuild Mysql. Optional parameter "env", default "dev". Example: make rebuild-db env=test
	@docker-compose exec php-fpm env XDEBUG_MODE=off php bin/console -e "${ENVIRONMENT}" doctrine:database:drop -f --if-exists
	@docker-compose exec php-fpm env XDEBUG_MODE=off php bin/console -e "${ENVIRONMENT}" doctrine:database:create
	@docker-compose exec php-fpm env XDEBUG_MODE=off php bin/console -e "${ENVIRONMENT}" doctrine:migrations:migrate -n
	@docker-compose exec php-fpm env XDEBUG_MODE=off php bin/console -e "${ENVIRONMENT}" doctrine:fixtures:load -n
