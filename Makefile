# Makefile for Task Management API

.PHONY: up down logs migrate-seed test stan lint

up:
	docker-compose up -d

down:
	docker-compose down

logs:
	docker-compose logs -f

migrate-seed:
	docker-compose exec app php artisan migrate --seed

test:
	docker-compose exec app php artisan test

stan:
	docker-compose exec app ./vendor/bin/phpstan analyse

lint:
	docker-compose exec app ./vendor/bin/pint --test