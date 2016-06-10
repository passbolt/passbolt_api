CakeDC Migrations Plugin
========================

[![Bake Status](https://secure.travis-ci.org/CakeDC/migrations.png?branch=master)](http://travis-ci.org/CakeDC/migrations)
[![Downloads](https://poser.pugx.org/CakeDC/migrations/d/total.png)](https://packagist.org/packages/CakeDC/migrations)
[![Latest Version](https://poser.pugx.org/CakeDC/migrations/v/stable.png)](https://packagist.org/packages/CakeDC/migrations)

The **Migrations** plugin enables developers to quickly and easily manage and migrate between database schema versions.

As an application is developed, changes to the database may be required, and managing that in teams can get extremely difficult. The **Migrations** plugin enables you to share and coordinate database changes in an iterative manner, removing the complexity of handling these changes.

* **Console:** The console script allows you to run migrations up and down.
* **Installation:** Migrations can also be run programmatically via an installer script.
* **ORM:** The **Migrations** plugin makes use of the *CakePHP* ORM and supports all databases provided by the framework.

This is NOT a Backup Tool
-------------------------

We highly recommend to not run the **Migrations** plugin in a production environment directly *without* doing a backup first.

However, you can make use of the ```before()``` and ```after()``` callbacks in the migration files to add logic which triggers a backup script.

Requirements
------------

* CakePHP 2.5.4+
* PHP 5.2.8+

Documentation
-------------

For documentation, as well as tutorials, see the [Docs](Docs/Home.md) directory of this repository.

Support
-------

For bugs and feature requests, please use the [issues](https://github.com/CakeDC/migrations/issues) section of this repository.

Commercial support is also available, [contact us](http://cakedc.com/contact) for more information.

Contributing
------------

This repository follows the [CakeDC Plugin Standard](http://cakedc.com/plugin-standard). If you'd like to contribute new features, enhancements or bug fixes to the plugin, please read our [Contribution Guidelines](http://cakedc.com/contribution-guidelines) for detailed instructions.

License
-------

Copyright 2007-2014 Cake Development Corporation (CakeDC). All rights reserved.

Licensed under the [MIT](http://www.opensource.org/licenses/mit-license.php) License. Redistributions of the source code included in this repository must retain the copyright notice found in each file.
