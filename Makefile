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

.PHONY: up
up: ## Start containers
	@docker-compose up -d

.PHONY: status
status: ## Show containers status
	@docker-compose ps

.PHONY: stop
stop: ## Stop containers
	@docker-compose stop

.PHONY: console
console: ## Symfony console, Optional parameter "command". Example: make console command=debug:autowiring
	@docker-compose exec php-fpm bin/console ${command}

.PHONY: cc
cc: ## Clear the cache. Optional parameter "env", default "dev". Example: make cc env=prod
	@docker-compose exec php-fpm rm -rf var/cache/${ENVIRONMENT}/*
	@docker-compose exec php-fpm php bin/console cache:warmup -e "${ENVIRONMENT}" --quiet

.PHONY: shell
shell: ## Interactive shell inside docker
	@docker-compose exec php-fpm sh

.PHONY: composer-install
composer-install: ## Composer install
	@docker-compose exec php-fpm composer install

.PHONY: purge-queues
purge-queues: ## Purge rabbitmq queues
	@docker-compose exec rabbitmq rabbitmqctl stop_app
	@docker-compose exec rabbitmq rabbitmqctl reset
	@docker-compose exec rabbitmq rabbitmqctl start_app

.PHONY: domain-events
domain-events: ## Consume domain events from rabbitmq
	@docker-compose exec php-fpm php bin/console messenger:consume ampqp

.PHONY: phpstan
phpstan: ## Run phpstan level 5
	@docker-compose exec php-fpm php vendor/bin/phpstan analyse -c phpstan.neon

.PHONY: phpcs
phpcs: ## Run phpcs PSR12
	@docker-compose exec php-fpm php vendor/bin/phpcs --report=code --colors --extensions=php --standard=PSR12 -p src

.PHONY: event-log
event-log: ## Tail event log. Optional parameter "env", default "dev". Example: make event-log env=prod
	@docker-compose exec php-fpm tail -f var/log/domain_event_"${ENVIRONMENT}".log

.PHONY: db
db: ## Mysql
	@docker-compose exec php-fpm php bin/console doctrine:migrations:migrate --no-interaction

