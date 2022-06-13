.DEFAULT_GOAL=help

DOCKER_COMPOSE = docker-compose
USE_BUILDKIT = COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1

help:
	@awk -F ':|##' '/^[^\t].+?:.*?##/ {\
		printf "\033[36m%-20s\033[0m %s\n", $$1, $$NF \
		}' $(MAKEFILE_LIST)

up: ## Create and start app
	@$(USE_BUILDKIT) $(DOCKER_COMPOSE) up -d --build

down: ## Stop and remove app
	@$(DOCKER_COMPOSE) down --rmi local

start: ## Start app
	@$(DOCKER_COMPOSE) start

stop: ## Stop app
	@$(DOCKER_COMPOSE) stop

ps: ## List containers
	@$(DOCKER_COMPOSE) ps

logs: ## Show logs
	@$(DOCKER_COMPOSE) logs -f

cli: ## Run php cli
	@$(DOCKER_COMPOSE) exec php sh

test: ## Run phpunit
	@$(DOCKER_COMPOSE) exec php ./bin/phpunit

setup: ## Setup project
	@$(DOCKER_COMPOSE) exec php composer install
	@$(DOCKER_COMPOSE) exec php ./bin/console d:d:d --force
	@$(DOCKER_COMPOSE) exec php ./bin/console d:d:c
	@$(DOCKER_COMPOSE) exec php ./bin/console d:m:m --no-interaction
	@$(DOCKER_COMPOSE) restart messenger
	@$(DOCKER_COMPOSE) restart outbox

install: ## Run composer install
	@$(DOCKER_COMPOSE) exec php composer install

migrate: ## Run doctrine migrate
	@$(DOCKER_COMPOSE) exec php ./bin/console d:m:m --no-interaction

migration-generate: ## Run doctrine migrate generate
	@$(DOCKER_COMPOSE) exec php ./bin/console d:m:g

db-recreate: ## Recreate database
	@$(DOCKER_COMPOSE) exec php ./bin/console d:d:d --force
	@$(DOCKER_COMPOSE) exec php ./bin/console d:d:c
	@$(DOCKER_COMPOSE) exec php ./bin/console d:m:m --no-interaction

cc: ## Clear cache
	@$(DOCKER_COMPOSE) exec php ./bin/console c:c

clear-log: ## Clear logs
	@$(DOCKER_COMPOSE) exec php rm ./var/log/*

consume-stop: ## Messenger and outbox stop
	@$(DOCKER_COMPOSE) stop messenger
	@$(DOCKER_COMPOSE) stop outbox

consume-restart: ## Messenger and outbox restart
	@$(DOCKER_COMPOSE) restart messenger
	@$(DOCKER_COMPOSE) restart outbox

consume-messenger: ## Messenger messages consume
	@$(DOCKER_COMPOSE) exec php ./bin/console messenger:consume high_priority normal_priority

consume-outbox: ## Outbox messages consume
	@$(DOCKER_COMPOSE) exec php ./bin/console andreo:eventsauce:message-outbox:consume --sleep=2

fix-cs: ## Fix cs
	@$(DOCKER_COMPOSE) exec php tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --allow-risky=yes