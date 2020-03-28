symfony server:start
symfony server:ca:install
127.0.0.1:8000

https://symfony.com/doc/current/page_creation.html
	https://symfony.com/doc/current/routing.html
	https://symfony.com/doc/current/controller.html
		https://symfony.com/doc/current/doctrine.html
	https://symfony.com/doc/current/templates.html
	https://symfony.com/doc/current/configuration.html

composer require annotations --> route dans le controller 
composer require twig --> install template twig
composer require symfony/maker-bundle --dev --> pour que symfony génère les controllers
composer require symfony/orm-pack --> pour installer doctrine
	php bin/console doctrine:database:create
	php bin/console make:migration