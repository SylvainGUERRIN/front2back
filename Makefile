# Executables
#EXEC_PHP      = php
COMPOSER      = composer
GIT           = git
YARN          = yarn

## Install vendors according to the current composer.lock file
install:
	$(COMPOSER) install --no-progress --prefer-dist --optimize-autoloader

unit-tests:
	php bin/phpunit --testsuite unit

functional-tests:
	php bin/phpunit --testsuite functional

fixtures-test:
	php bin/console doctrine:fixtures:load -n --env=test

database-test:
	php bin/console doctrine:database:drop --if-exists --force --env=test
	php bin/console doctrine:database:create --env=test
	php bin/console doctrine:schema:update --force --env=test

prepare-test:
	make database-test
	make fixtures-test
