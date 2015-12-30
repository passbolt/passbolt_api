Installation
============

Using Composer
--------------

Assuming you're mostly familiar with the basics of composer, you'll only have to add the plugin to your `required` section of `composer.json`.

```js
{
	"require": {
		"burzum/cakephp-imagine-plugin": "3.0.*@dev",
	}
}
```

Using Git
---------

You need to init the git sub module of imagine:

```
git submodule update --init
```

If you're **not** using the submodule get it from https://github.com/avalanche123/Imagine

Assuming that you're *not* using composer by installing the plugin via git, you'll have to add the namespace of the plugin and the vendor lib manually to whatever autoloader you're using.

Bootstrap
---------

Load the plugin as any other plugin:

```php
Plugin::load('Burzum/Imagine');
```

Configure Salt for Imagine
--------------------------

You need to configure a salt for Imagine security functions.

```php
Configure::write('Imagine.salt', 'your-salt-string-here');
```

We do not use Security.salt on purpose because we do not want to use the same salt here for security reasons.
