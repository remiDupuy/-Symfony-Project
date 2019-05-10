SHOWROOM
====

* Dependencies
```
php >= 5.5.9
composer
```

For minors requirements checkout the docs : https://symfony.com/doc/3.4/reference/requirements.html

* Installation

To install the project, here is the following instructions : 
```bash
git clone  https://github.com/remiDupuy/-Symfony-Project 
cd -Symfony-Project/
composer install
bin/console doctrine:database:create
bin/console doctrine:database:migrate
```

If a problem occurs checkout the symfony documentation : https://symfony.com/doc/3.4/setup.html

* Running the project
```bash
cd -Symfony-Project/
bin/console server:start
```

You can access the project with this address : http://localhost:{myPort}/


