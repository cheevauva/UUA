COMPOSE_DEV=./docker-compose.yaml
COMPOSE_NAME=uua

Arguments := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))

up:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) up -d
build:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) build --progress=plain
build_nocache:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) build --no-cache --progress=plain
down:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) down
php:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) exec php sh
logs:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) logs -f
ps:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) ps
composer_update:; docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) exec -T php composer update
phpstan:; docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) exec -T php ./vendor/bin/phpstan  analyse -c phpstan.neon --memory-limit=2000M --no-progress
phpstan_baseline:; docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) exec -T php vendor/bin/phpstan analyse -c phpstan.neon --generate-baseline --memory-limit=512M -vv
phpunit:; docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) exec -T php php -d pcov.directory=. ./vendor/bin/phpunit -c phpunit.xml --display-warnings --fail-on-warning --display-deprecations
hosts:; chmod +x ./hosts.sh && rm -f ./hosts.txt && ./hosts.sh > hosts.txt

