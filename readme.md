
for the first time:
	docker compose up -d --build
	docker compose exec app bash
	composer install --no-interaction --prefer-dist // install dependencies

for the first time only for development (if you need a seeder):
	composer require --dev doctrine/doctrine-fixtures-bundle
	composer require fzaninotto/faker

for everyday work:
	docker compose up -d
	docker compose stop
	

diff. usefull commands:
	docker compose exec postgres psql -U test -d test

	docker compose exec --user root app bash