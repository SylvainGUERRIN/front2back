# Executables
#EXEC_PHP      = php
COMPOSER      = composer
GIT           = git
YARN          = yarn

## Install vendors according to the current composer.lock file
#install: composer.json
#	$(COMPOSER) install
install: composer.lock
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

## —— Yarn 🐱 / JavaScript —————————————————————————————————————————————————————
dev: ## Rebuild assets for the dev env
	$(YARN) install --check-files
	$(YARN) run encore dev

watch: ## Watch files and build assets when needed for the dev env
	$(YARN) run encore dev --watch

build: ## Build assets for production
	$(YARN) run encore production

lint-js: ## Lints JS coding standarts
	$(NPX) eslint assets/js

fix-js: ## Fixes JS files
	$(NPX) eslint assets/js --fix

## —— Coding standards ✨ ——————————————————————————————————————————————————————
cs: lint-php lint-js ## Run all coding standards checks

static-analysis: stan ## Run the static analysis (PHPStan)

stan: ## Run PHPStan
	$(PHPSTAN) analyse -c configuration/phpstan.neon --memory-limit 1G

lint-php: ## Lint files with php-cs-fixer
	$(PHP_CS_FIXER) fix --allow-risky=yes --dry-run

fix-php: ## Fix files with php-cs-fixer
	$(PHP_CS_FIXER) fix --allow-risky=yes

## —— Tests ✅ —————————————————————————————————————————————————————————————————
test: phpunit.xml check ## Run main functional and unit tests
	$(eval testsuite ?= 'main') # or "external"
	$(eval filter ?= '.')
	$(PHPUNIT) --testsuite=$(testsuite) --filter=$(filter) --stop-on-failure

# Snippet L165+4 => templates/blog/posts/_123.html.twig

test-all: phpunit.xml ## Run all tests
	$(PHPUNIT) --stop-on-failure
