	      ____                  __          ____
	     / __ \____  _____ ____/ /_  ____  / / /_
	    / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/
	   / ____/ /_/ (__  |__  ) /_/ / /_/ / / /_
	  /_/    \__,_/____/____/_.___/\____/_/\__/
	
	The password management solution
	(c) 2012-2015 passbolt.com

Install
=========

You will need to install php5 and the following modules directly or using pear/pecl
- mod_rewrite http://book.cakephp.org/2.0/en/installation/url-rewriting.html
- gd / imagemagick
- gnupg http://php.net/manual/en/gnupg.installation.php

Clone the repository and associated submodules
```
	git clone git@github.com:passbolt/passbolt
	cd passbolt
	git submodule update --init
```

Copy the core configuration file, change the cypher seed and salt
```
	cp app/Config/core.php.default app/Config/core.php
```

Copy the database configuration file and edit the credentials
```
	cp app/Config/database.php.default app/Config/database.php
	nano app/Config/database.php
```

Copy the app configuration file
```
	cp app/Config/app.php.default app/Config/app.php
```

Install Composer app files (to install vendor and plugin dependencies).
You will need a working version of composer. See https://getcomposer.org
```
	cd app
	composer install --no-dev
```

Install the front-end dependencies. You will need to have bower and grunt installed.
```
	bower install
	grunt lib-deploy
```


Run the install script from the cakephp root with the data flag set
if you want to install test data.
```
  cd ..
	./app/Console/cake install [--data=1]
```
Check if it works!


How to edit the LESS/CSS files?
=========

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
	bower install
	grunt styleguide-deploy
```
Make sure Grunt watch for less changes and compile them into CSS
```
	grunt watch
```
Edit one LESS file to see if it works!
Make sure that if you need to make change the styleguide to request changes upstream.


Prepare the production release
=========

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


Emails settings
===============

For images that are send in emails, we need to tell cakephp what is the base url.
To fix this, add/uncomment this line in Config/core.php
```
	Configure::write('App.fullBaseUrl', 'http://{your domain without slash}');
```
Emails are placed in a queue that needs to be processed by a CakePhp Shell. To do so, execute the following command, or launch it at regular intervals through cron.
From your app folder :
```
	Console/cake EmailQueue.sender
```
You can also see the corresponding documentation here https://github.com/lorenzo/cakephp-email-queue


Test suite
==========

To execute the test suite, you will need to install phpunit.
The simplest way is to do it through composer. A composer file is already included at the root of the passbolt installation.
```
cd /var/www/passbolt
composer install
```

Credits
=========

Team Rocket
------
Kevin, Cedric, Remy, Aurelie, Ismael & Myriam


CakePHP
--------

CakePHP is a rapid development framework for PHP which uses commonly known design patterns like Active Record, Association Data Mapping, Front Controller and MVC.
Our primary goal is to provide a structured framework that enables PHP users at all levels to rapidly develop robust web applications, without any loss to flexibility.

[CakePHP](http://www.cakephp.org) - The rapid development PHP framework

[Cookbook](http://book.cakephp.org) - THE Cake user documentation; start learning here!

[Plugins](http://plugins.cakephp.org/) - A repository of extensions to the framework

[The Bakery](http://bakery.cakephp.org) - Tips, tutorials and articles

[API](http://api.cakephp.org) - A reference to Cake's classes

[CakePHP TV](http://tv.cakephp.org) - Screen casts from events and video tutorials

[The Cake Software Foundation](http://cakefoundation.org/) - promoting development related to CakePHP

[Our Google Group](http://groups.google.com/group/cake-php) - community mailing list and forum

[#cakephp](http://webchat.freenode.net/?channels=#cakephp) on irc.freenode.net - Come chat with us, we have cake.

[Q & A](http://ask.cakephp.org/) - Ask questions here, all questions welcome

[Lighthouse](http://cakephp.lighthouseapp.com/) - Got issues? Please tell us!

[![Bake Status](https://secure.travis-ci.org/cakephp/cakephp.png?branch=master)](http://travis-ci.org/cakephp/cakephp)

![Cake Power](https://raw.github.com/cakephp/cakephp/master/lib/Cake/Console/Templates/skel/webroot/img/cake.power.gif)
