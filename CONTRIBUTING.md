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

## How do I contribute to the the js application

Clone the appjs repository in a separate folder
```
git clone https://github.com/passbolt/passbolt-appjs.git
```

In your passbolt_api folder install the javascript dependencies
```
npm install
```

Link the source of passbolt-appjs project to your passbolt_api project
```
cd node_modules
rm -fr passbolt-appjs
npm link ../../passbolt-appjs
cd ../
```

Listen to any change on the passbolt-appjs product
```
grunt appjs-watch
```

If you want to save the browser refresh operation, and you are aware about the security implication, you can
install browser-sync
```
npm install grunt-browser-sync
```

Listen to the appjs change and refresh the browser
```
grunt appjs-watch-browser-sync
```