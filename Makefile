DOCKER_COMPOSE=/usr/local/bin/docker-compose
COMPOSE=${DOCKER_COMPOSE} -f devops/docker-compose/docker-compose.yml
OS := $(shell uname -s)
ARCH := $(shell uname -m)

help: ## Help
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

phpstan: ## execute phpstan to max level
	vendor/bin/phpstan analyze --level 8 src

phpcsfixer: ## execute phpcsfixer
	vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix

lint: phpcsfixer phpstan ## execute all quality tools

setup: ## install docker compose
	sudo curl -Ls https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(OS)-$(ARCH) -o ${DOCKER_COMPOSE}
	sudo chmod +x /usr/local/bin/docker-compose

up: ## start your local env (https://localhost)
	${COMPOSE} up --build -d

down: ## stop your local env
	${COMPOSE} down

restart: ## restart your local env
	${COMPOSE} restart

reset: ## reset your local env
	${COMPOSE} rm -f -v -s
	${COMPOSE} up --build -d

exec: ## execute a shell inside your php container
	${COMPOSE} exec php /bin/bash