
	      ____                  __          ____
	     / __ \____  _____ ____/ /_  ____  / / /_
	    / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/
	   / ____/ /_/ (__  |__  ) /_/ / /_/ / / /_
	  /_/    \__,_/____/____/_,___/\____/_/\__/
	
	The open-source password management solution for teams
	(c) 2016 Bolt Softwares Pvt Ltd
	https://www.passbolt.com


License
==============

Passbolt is distributed under [Affero General Public License v3](http://www.gnu.org/licenses/agpl-3.0.html)

About Passbolt
==============

Passbolt is an open source password manager for teams. It allows to securely share and store credentials.
For instance, the wifi password of your office, or the administrator password of a router, or your organisation social media account password,
all of them can be secured using Passbolt.

Passbolt is different from the other password managers because:
- It is free & open source;
- It is respectful of privacy;
- It is primarily designed for teams and not individuals;
- It is based on OpenPGP, a proven cryptographic standard;
- It is easy to use for both novice and IT professionals alike.
- It is extensible thanks to its restful API

Find out more more : [https://www.passbolt.com](https://www.passbolt.com "Passbolt Homepage")


In a glimpse
------------

<a href="https://raw.githubusercontent.com/passbolt/passbolt_styleguide/master/src/img/screenshots/teaser-screenshot-login.png" rel="passwords list">
![Passwords list](https://raw.githubusercontent.com/passbolt/passbolt_styleguide/master/src/img/screenshots/teaser-screenshot-login-275.png)
</a>
<a href="https://raw.githubusercontent.com/passbolt/passbolt_styleguide/master/src/img/screenshots/teaser-screenshot4.png" rel="passwords list">
![Passwords list](https://raw.githubusercontent.com/passbolt/passbolt_styleguide/master/src/img/screenshots/teaser-screenshot4-275.png)
</a>
<a href="https://raw.githubusercontent.com/passbolt/passbolt_styleguide/master/src/img/screenshots/teaser-screenshot-share.png" rel="passwords list">
![Passwords list](https://raw.githubusercontent.com/passbolt/passbolt_styleguide/master/src/img/screenshots/teaser-screenshot-share-275.png)
</a>


Using passbolt
==============

- To try a demo of passbolt: https://demo.passbolt.com
- To install passbolt on your own machine, follow these instructions : https://www.passbolt.com/help/tech/install
- To know more about passbolt: https://www.passbolt.com

Contributing to passbolt
========================

If you are a programmer and wish to contribute / extend passbolt, here is what you need to know.

Prerequisite
------------
You will need a webserver with SSL and url rewriting enabled :
- Url rewriting http://book.cakephp.org/2.0/en/installation/url-rewriting.html

You will need to install php5 and the following modules directly or using pear/pecl:
- Imagick http://php.net/manual/en/book.imagick.php
- gnupg http://php.net/manual/en/gnupg.installation.php
- Composer https://getcomposer.org/

The following modules are greatly recommended :
- Memcached http://php.net/manual/en/memcached.setup.php

For testing, code styling and coverage :
- Phpunit https://phpunit.de/
- Curl http://php.net/manual/en/curl.installation.php
- PhpCS https://pear.php.net/manual/en/package.php.php-codesniffer.php
- XDebug http://xdebug.org/

Frequently Asked Questions
===========================

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
-------------------------------
To update the PHP libraries, go to /app, and
```
	composer install --no-dev
```
Then execute all the unit tests and selenium tests, and if everything passes it can be commited and pushed on the git repo.


How to update the Javascript libraries
--------------------------------------
```
	npm install && npm update
	grunt lib-deploy
```
Then execute all the selenium tests, and if everything passes it can be commited and pushed on the git repo.


How do I recompile the Javascript build?
----------------------------------------

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
 - [Cédric Alfonsi](https://github.com/cedricalfonsi "Cédric Alfonsi")
 - [Rémy Bertot](https://github.com/stripthis "Rémy Bertot")
 - [Kevin Muller](https://github.com/kevinmuller "Kevin Muller")
 
Special thanks:
Ismail, Myriam, Aurelie, Anhad, Shruti, Arthur, Janosch, Diego!

https://www.passbolt.com/credits


Legal
=========

Terms of service
----------------
https://www.passbolt.com/terms

Privacy Policy
--------------
https://www.passbolt.com/privacy