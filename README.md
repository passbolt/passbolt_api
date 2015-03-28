	      ____                  __          ____
	     / __ \____  _____ ____/ /_  ____  / / /_
	    / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/
	   / ____/ /_/ (__  |__  ) /_/ / /_/ / / /_
	  /_/    \__,_/____/____/_.___/\____/_/\__/
	
	The password management solution
	(c) 2012-2013 passbolt.com

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
Install Composer files (to install vendor and plugin dependencies).
```
	cd app && php composer.phar install
```
Run the install script from the cakephp root
```
  cd ..
	./app/Console/cake install
```
Check if it works!


How to edit the LESS/CSS files?
=========

Install grunt
```
	npm install -g grunt-cli
```
Install the needed modules defined in the grunt config
```
	npm install
```
Make sure Grunt watch for less changes and compile them into CSS
```
	grunt watch
```
Edit one LESS file to see if it works!

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
