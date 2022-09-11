UID := $(shell id -u)

default: build up

build:
	docker-compose build

up:
	docker-compose up -d

stop:
	docker-compose stop

down:
	docker-compose down --volumes --rmi local --remove-orphans

php:
	docker-compose exec -u$(UID) php bash

install:
	docker-compose exec -u$(UID) php composer install
	docker-compose exec -u$(UID) php ./bin/console doc:mig:mig --no-interaction
	docker-compose exec -u$(UID) php ./bin/console doc:fix:load --no-interaction --purge-with-truncate
    docker-compose exec -u$(UID) php npm install
    docker-compose exec -u$(UID) php npm run build

reinstall: down build up install
