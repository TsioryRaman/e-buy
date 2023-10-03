CONSOLE= bin/console

.PHONY: ebuy@start

#################
#   COMPOSER    #
#################
composer@vendor: composer.json
	composer install

composer@require:
	composer require $(bundle)

composer@remove:
	composer remove $(bundle)

#################
#   YARN    	#
#################
yarn@node_modules: package.json
	yarn install

yarn@command:
	$(command)

yarn@add:
	yarn add $(package)

yarn@remove:
	yarn remove $(package)

yarn@serve:
#	cd public && ln -s ../assets assets && cd .. &&
	yarn run dev &

yarn@start: yarn@node_modules yarn@serve

symfony@serve: symfony@db
	symfony serve

symfony@db: composer@vendor
#	$(CONSOLE) doctrine:database:drop --force --if-exists
#	$(CONSOLE) doctrine:database:create --if-not-exists
#	$(CONSOLE) doctrine:migrations:migrate -n
#	$(CONSOLE) doctrine:schema:update --force
#	$(CONSOLE) doctrine:fixtures:load -n

symfony@migration:
	bin/console make:migration

symfony@fixtures:
	bin/console doctrine:fixtures:load

symfony@dropdata:
	bin/console doctrine:database:drop --force

symfony@migrate:
	bin/console doctrine:migration:migrate

ebuy@start: yarn@start symfony@serve

