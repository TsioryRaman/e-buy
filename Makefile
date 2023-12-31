EXEC_AS_ROOT= docker compose exec php-fpm
EXEC_AS_WEB= docker compose exec --user=www-data php-fpm
CONSOLE= $(EXEC_AS_WEB) bin/console

.PHONE: console

console:
	$(EXEC_AS_WEB) bin/console
#################
#   DOCKER    #
#################
docker@build: docker-compose.yml
	docker compose build

docker@up: docker@build
	docker compose up -d

de@assets: 
	$(EXEC_AS_WEB) assets:install

CONSOLE= bin/console

.PHONY: ebuy@start

#################
#   COMPOSER    #
#################
composer@vendor: composer.json
	$(EXEC_AS_WEB) composer install

composer@require:
	$(EXEC_AS_WEB) composer require $(bundle)

composer@remove:
	$(EXEC_AS_WEB) composer remove $(bundle)


#################
#   TYPESENSE  	#
#################
typesense@index:
	$(EXEC_AS_WEB) bin/console app:index

#################
#   YARN    	#
#################
yarn@node_modules: package.json
	$(EXEC_AS_WEB) yarn install

yarn@command:
	$(EXEC_AS_WEB) $(command)

yarn@add:
	$(EXEC_AS_WEB) yarn add $(package)

yarn@remove:
	$(EXEC_AS_WEB) yarn remove $(package)

yarn@serve:
	$(EXEC_AS_WEB) yarn run dev &

yarn@start: yarn@node_modules yarn@serve

symfony@serve: symfony@db
	symfony serve

symfony@wait-for-db:
	$(EXEC_AS_ROOT) php -r "set_time_limit(60);for(;;){if(@fsockopen('mysql',3306)){break;}echo \"Waiting for MySQL\n\";sleep(1);}"

symfony@db: composer@vendor symfony@wait-for-db
	$(EXEC_AS_WEB) bin/console doctrine:database:drop --force --if-exists
	$(EXEC_AS_WEB) bin/console doctrine:database:create --if-not-exists
	$(EXEC_AS_WEB) bin/console doctrine:migrations:migrate -n
	$(EXEC_AS_WEB) bin/console doctrine:schema:update --force
	$(EXEC_AS_WEB) bin/console doctrine:fixtures:load -n

#################
#   SYMFONY    	#
#################
migration:
	$(EXEC_AS_WEB) bin/console make:migration

fixtures:
	$(EXEC_AS_WEB) bin/console doctrine:fixtures:load

dropdata:
	$(EXEC_AS_WEB) bin/console doctrine:database:drop --force

migrate:
	$(EXEC_AS_WEB) bin/console doctrine:migration:migrate

#################
#   MAKE    	#
#################
entity:
	$(EXEC_AS_WEB) bin/console make:entity $(entity)

#################
#   EBUY    	#
#################
ebuy@run: symfony@db 

ebuy@start: docker@up ebuy@run typesense@index

ebuy@stop:
	docker compose down

dev:
	docker compose up
build:
	docker compose build

ebuy@restart: ebuy@stop ebuy@start