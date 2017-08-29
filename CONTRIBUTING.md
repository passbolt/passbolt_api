# Developers FAQ
## Prerequisite
Make sure you have the developement dependencies install.
```
composer install --dev
```

## How do I run the unit tests
- Configure your test database in app.php datasources section.
- Run phpunit:
```
./vendor/bin/phpunit
```

## How do I check the code standards
- Add the CakePHP standard to your phpcs config. You must specify an absolute path.
```
./vendor/bin/phpcs --config-set installed_paths ~/www/passbolt_api/vendor/cakephp/cakephp-codesniffer
```
- Run phpcs in the relevant directories
```
./vendor/bin/phpcs --standard=CakePHP config
./vendor/bin/phpcs --standard=CakePHP src
./vendor/bin/phpcs --standard=CakePHP tests
```
