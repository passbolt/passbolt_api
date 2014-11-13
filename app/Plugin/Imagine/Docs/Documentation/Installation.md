Installation
============

Using Git
---------

You need to init the git submodule of imagine

```
git submodule update --init
```

If you're **not** using the submodule get it from https://github.com/avalanche123/Imagine

Copy Imagine into the plugins vendor folder Vendor/Imagine, the root of the Imagine package should be inside this folder. Vendor/Imagine/README.md should be present if you placed the code correctly.

Load the imagine plugin in your apps ```app/Config/bootstrap.php``` file including it's bootstrap.

    CakePlugin::load('Imagine' => array('bootstrap' => true));

The bootstrap.php of the plugin will just register a SPL autoloader for the Imagine namespace. If you didn't put Imagine in the plugins vendor folder you'll have set your own autoloader up to load it.

Using Composer
--------------

Assuming you're mostly familiar with the basics of composer just take a look at the ```extra```section. You'll have to define the installer path for the imagine plugin so that composer can put it in the right place with the right name.

```js
{
    "config": {
        "vendor-dir": "app/Vendor/",
        "preferred-install": "source"
    },
    "require": {
        "burzum/cakephp-imagine-plugin": "dev-master",
        "imagine/imagine": "dev-master"
    },
    "extra": {
        "installer-paths": {
            "app/Plugin/Imagine": ["burzum/cakephp-imagine-plugin"]
        }
    }
}
```

Bootstrap
---------

If you're not using composer make sure you load the plugin with bootstrap enabled in your apps `bootstrap.php`. Or copy the content from it to your apps bootstrap.php. If you don't do that the vendor libs won't be loaded.

```php
CakePlugin::load('Imagine', array(
	'bootstrap' => true
));
```

Configure Salt for Imagine
--------------------------

You need to configure a salt for Imagine security functions.

```php
Configure::write('Imagine.salt', 'your-salt-string-here');
```

We do not use Security.salt on purpose because we do not want to use the same salt here for security reasons.
