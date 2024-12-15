
for the first time:
	rename .env.example to .env (or create .env with .env.example content) 
	docker compose up -d --build
	docker compose exec app bash
	composer install --no-interaction --prefer-dist // install dependencies
	docker exec -it symfony_postgres psql -U test
	CREATE DATABASE test;
	exit
	docker compose exec app bash
	php bin/console doctrine:migrations:migrate
	php bin/console doctrine:fixtures:load       (I had some problems with app/vendor/fzaninotto/faker/src/Faker/Provider/Lorem.php  
		like: join(): Argument #2 ($array) must be of type ?array, string given solution:
		change arguments position like: join(' ', $words)   (I had 2 similar places))
	npm install bootstrap


for the first time only for development (if you need a seeder):
	composer require --dev doctrine/doctrine-fixtures-bundle
	composer require fzaninotto/faker

for everyday work:
	docker compose up -d
	docker compose stop
	npm run (dev)(build)   to compile css and js on the fly or once  !!!inside container(docker compose exec app bash)!!!
	php bin/console messenger:consume async -vv (start the worker to sending mails)


diff. usefull commands:
	docker compose exec postgres psql -U test -d test (or inside container execute: psql -U test -d test)

	docker compose exec --user root app bash

	after creating new entity(php bin/console make:entity) execute:
		php bin/console doctrine:migrations:diff
		php bin/console doctrine:migrations:migrate