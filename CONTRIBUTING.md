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
composer run-script test
```

## How do I check the code standards
- To display the error and warning
```
composer run-script cs-check
```
- To autofix what is fixable
```
composer run-script cs-fix
```

## How to regenerate the fixtures
```
sudo su -s /bin/bash -c "./bin/cake PassboltTestData.fixturize default" www-data
```
