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
	docker-compose exec php bash

install:
	docker-compose exec php composer install
	docker-compose exec php npm install
	docker-compose exec php npm run build
	docker-compose exec php php bin/console doc:mig:mig --no-interaction
	docker-compose exec php php bin/console doc:fix:load --no-interaction --purge-with-truncate


reinstall: down build up install
