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
