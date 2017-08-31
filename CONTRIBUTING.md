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
- Check the CakePHP standard is present
```
./vendor/bin/phpcs -i
The installed coding standards are MySource, PEAR, PSR1, PSR2, Squiz, Zend and CakePHP
```
- Run phpcs in the relevant directories
```
./bin/checkstyle
```
