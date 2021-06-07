# Installation

[Retour au sommaire](index.md)

# Initialisation Behat
Initialisation de behat  
commande: php vendor/behat/behat/bin/behat --init  
lancer: php vendor/behat/behat/bin/behat

# Initialisation BDD

# Installation pour les tests
Initialisation de la BDD  
php bin/console doctrine:database:drop --if-exists --force --env=test  
php bin/console doctrine:database:create --env=test  
php bin/console doctrine:schema:update --force --env=test  
Initialisation des fixtures
php bin/console doctrine:fixtures:load -n --env=test

# Installation locale
Initialisation de la BDD  
php bin/console doctrine:database:drop --if-exists --force  
php bin/console doctrine:database:create  
php bin/console doctrine:schema:update --force

# Lancer serveur Symfony
symfony serve

# Installer dependencies from Yarn
yarn install

# Checker dependencies from Yarn
yarn check

# Update all packages from Yarn
yarn upgrade 

# Update one package from Yarn
yarn upgrade name-of-package

# Update add package from Yarn
yarn add nom-du-package@version-du-package

# Compile assets once
yarn encore dev

# Compile assets automatically when files changes
yarn encore dev --watch

# On deploy, create production build
yarn encore production
