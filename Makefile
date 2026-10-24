COMPOSE_DEV=./docker-compose.yaml
COMPOSE_NAME=uua

Arguments := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))

up:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) up -d
build:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) build --no-cache
down:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) down
php:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) exec php sh
logs:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) logs -f
ps:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) ps
composer:; docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) exec -T php composer $(filter-out $@,$(MAKECMDGOALS))
phpstan:; docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) exec -T php ./vendor/bin/phpstan  analyse $(filter-out $@,$(MAKECMDGOALS)) -c phpstan.neon --memory-limit=2000M --no-progress
phpstan_baseline:; docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) exec -T php vendor/bin/phpstan analyse -c phpstan.neon --generate-baseline --memory-limit=512M -vv
phpunit:; docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) exec -T php ./vendor/bin/phpunit $(filter-out $@,$(MAKECMDGOALS)) -c phpunit.xml --display-warnings --fail-on-warning --display-deprecations
optimize-hosts: ; docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) exec  -u 0 php bash /var/www/html/entrypoint.sh

