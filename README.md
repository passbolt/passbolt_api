
	      ____                  __          ____
	     / __ \____  _____ ____/ /_  ____  / / /_
	    / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/
	   / ____/ /_/ (__  |__  ) /_/ / /_/ / / /_
	  /_/    \__,_/____/____/_,___/\____/_/\__/
	
	The open-source password management solution for teams
	(c) 2015-present Bolt Softwares Pvt Ltd


Passbolt in a glimpse
=====================
<a href="https://raw.githubusercontent.com/passbolt/passbolt_styleguide/master/src/img/screenshots/teaser-screenshot-login.png" rel="passwords list">
![Passwords list](https://raw.githubusercontent.com/passbolt/passbolt_styleguide/master/src/img/screenshots/teaser-screenshot-login-275.png)
</a>
<a href="https://raw.githubusercontent.com/passbolt/passbolt_styleguide/master/src/img/screenshots/teaser-screenshot4.png" rel="passwords list">
![Passwords list](https://raw.githubusercontent.com/passbolt/passbolt_styleguide/master/src/img/screenshots/teaser-screenshot4-275.png)
</a>
<a href="https://raw.githubusercontent.com/passbolt/passbolt_styleguide/master/src/img/screenshots/teaser-screenshot-share.png" rel="passwords list">
![Passwords list](https://raw.githubusercontent.com/passbolt/passbolt_styleguide/master/src/img/screenshots/teaser-screenshot-share-275.png)
</a>

Getting started
===============

Prerequisite
------------

You will need firefox browser with the passbolt plugin. Passbolt is compatible with firefox only at the moment.
The plugin repository is here : https://github.com/passbolt/passbolt_firefox

You will need a webserver with SSL enabled.

You will need to install php5 and the following modules directly or using pear/pecl:
- mod_rewrite http://book.cakephp.org/2.0/en/installation/url-rewriting.html
- Imagick http://php.net/manual/en/book.imagick.php
- gnupg http://php.net/manual/en/gnupg.installation.php
- Composer https://getcomposer.org/

For testing, code styling and coverage:
- Phpunit https://phpunit.de/
- Curl http://php.net/manual/en/curl.installation.php
- PhpCS https://pear.php.net/manual/en/package.php.php-codesniffer.php
- XDebug http://xdebug.org/

Getting the code
----------------

Clone the repository and associated submodules
```
	git clone https://github.com/passbolt/passbolt_firefox.git
	cd passbolt
```

Configuration
-------------

Copy the core configuration file, change the cypher seed and salt
```
	cp app/Config/core.php.default app/Config/core.php
```

Copy the database configuration file and edit the database name and credentials
```
	cp app/Config/database.php.default app/Config/database.php
	nano app/Config/database.php
```

Copy the app configuration file and check the settings
```
	cp app/Config/app.php.default app/Config/app.php
```

Set the email settings to be able to send emails
```
	nano app/Config/email.php
```

Installation script
-------------------

Run the install script from the cakephp root with the data flag set
if you want to install the test data add the relevant parameter.
```
  cd ..
	./app/Console/cake install [--data=[default|unittests|seleniumtests]]
```

Check if it works!


Emails settings
---------------
Emails are placed in a queue that needs to be processed by a CakePhp Shell.
To do so, execute the following command from your app folder :
```
	Console/cake EmailQueue.sender
```

You can also see the corresponding documentation here:
https://github.com/lorenzo/cakephp-email-queue

Or launch it at regular intervals through cron. For example in :
```
	﻿crontab -e
```

You can add a call to the script to run every minutes:
```
	* * * * * /var/www/passbolt/app/Console/cake EmailQueue.sender > /var/log/passbolt.log
```

See more: ﻿https://en.wikipedia.org/wiki/Cron


Frequently Asked Questions
===========================

Why am I getting a segmentation fault at install?
-------------------------------------------------

It is possible that your $GNUPGHOME is not set or not available to either the php CLI or Apache users thus causing
a segmentation fault.
- Check app.php if you don't have ssh access, it can be set at run time.
- Make sure the directory is accessible and writable for these users


Why are images not displayed in the emails?
--------------------------------------------

For images that are send in emails, we need to tell cakephp what is the base url.
To fix this, add/uncomment this line in Config/core.php
```
	Configure::write('App.fullBaseUrl', 'http://{your domain without slash}');
```

How to edit the LESS/CSS files?
-------------------------------

All the less and css files of passbolt are managed through a styleguide.
https://github.com/passbolt/passbolt_styleguide

Any modification in the style has to be first implemented in the styleguide.

To deploy the styleguide :

Install grunt and grunt
```
	npm install -g grunt-cli
```

Install the needed modules defined in the grunt config
```
	npm install
```

Install the styleguide
```
	grunt styleguide-deploy
```

Make sure Grunt watch for less changes and compile them into CSS
```
	grunt watch
```

Edit one LESS file to see if it works!
Make sure that if you need to make change to the styleguide to fork or request changes to be included upstream.


How do I run the unit tests?
----------------------------

To execute the test suite, you will need to install phpunit.
The simplest way is to do it through composer.
```
composer install
```

Make sure Debug is set to at least 1 in Config/app.php
You can then go to test.php and run the tests from there.
For example: ﻿http://localhost/passbolt/test.php

How do I run the selenium tests?
--------------------------------

Passbolt is provided with a suite of selenium tests.

The selenium test suite is available in a separate project :
https://github.com/passbolt/passbolt_selenium

How to regenerate the fixtures?
-------------------------------

The fixtures are generated from the Data shell and plugins tasks. It is better you change the unit tests data tasks,
install the data set and rexport the content as fixtures.
```
./app/Console/cake install --data=unittests
./app/Console/cake data export
```

Note that the tests are tightly coupled with the data. If you change it you may need to change the tests. You can add more record safely of course.

How to update the PHP libraries
-------------------
To update the PHP libraries, go to /app, and
```
	composer install --no-dev
```
Then execute all the unit tests and selenium tests, and if everything passes it can be commited and pushed on the git repo.

How to update the Javascript libraries
-------------------
```
	npm install && npm update
	grunt lib-deploy
```
Then execute all the selenium tests, and if everything passes it can be commited and pushed on the git repo.

How do I recompile the Javascript build?
----------------------------------

Install grunt if it hasn't yet been installed
```
	npm install -g grunt-cli
```

Install the needed modules defined in the grunt config
```
	npm install
```

Prepare the production release
```
	grunt production
```

CSS minified files should have been generated as the Javascript minified file.

How do I check the code standards?
----------------------------------

To run the sniffs for CakePHP coding standards, first you need to install the dev-dependencies with composer.
Make sure you use the 1.* branch of the cakephp sniffs otherwise the new Cakephp3/PSR2 standards will apply.
It depends on phpcs version 1.* as well and not the latest releases.
```
    composer require --dev "cakephp/cakephp-codesniffer=1.*"
```

Then let phpcs where to find the cakephp sniffs
```
    Vendor/bin/phpcs --config-set installed_paths Vendor/cakephp/cakephp-codesniffer
```

The you can run it like follow:
```
     Vendor/bin/phpcs --standard=CakePHP app/path/to/something
```

Credits
=========

Passbolt Team Rocket
--------------------

Design and programming :
 - [Kevin Muller](https://github.com/kevinmuller "Kevin Muller")
 - [Cédric Alfonsi](https://github.com/cedricalfonsi "Cédric Alfonsi")
 - [Remy Bertot](https://github.com/stripthis "Remy Bertot")
 
Unconditional support :
Ismail, Myriam, Aurelie, Anhad, Shruti, Arthur.

More :
https://www.passbolt.com/credits
