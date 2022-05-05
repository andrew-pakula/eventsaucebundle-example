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

install: ## Run composer install
	@$(DOCKER_COMPOSE) exec php composer install

migrate: ## Run doctrine migrate
	@$(DOCKER_COMPOSE) exec php ./bin/console d:m:m --no-interaction

messenger-stop: ## Messenger stop
	@$(DOCKER_COMPOSE) stop messenger

outbox-stop: ## Outbox stop
	@$(DOCKER_COMPOSE) stop outbox

messenger-restart: ## Messenger restart
	@$(DOCKER_COMPOSE) restart messenger

outbox-restart: ## Outbox restart
	@$(DOCKER_COMPOSE) restart outbox

messenger-consume: ## Messenger consume
	@$(DOCKER_COMPOSE) exec php ./bin/console messenger:consume high_priority normal_priority

outbox-consume: ## Outbox consume
	@$(DOCKER_COMPOSE) exec php ./bin/console andreo:event-sauce:outbox-process-messages --sleep=2

fix-cs: ## Fix cs
	@$(DOCKER_COMPOSE) exec php tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --allow-risky=yes