Installation
============

To install the plugin, place the files in a directory labelled "Migrations/" in your "app/Plugin/" directory.

Then, include the following line in your `app/Config/bootstrap.php` to load the plugin in your application.

```
CakePlugin::load('Migrations');
```

Git Submodule
-------------

If you're using git for version control, you may want to add the **Migrations** plugin as a submodule on your repository. To do so, run the following command from the base of your repository:

```
git submodule add git@github.com:CakeDC/migrations.git app/Plugin/Migrations
```

After doing so, you will see the submodule in your changes pending, plus the file ".gitmodules". Simply commit and push to your repository.

To initialize the submodule(s) run the following command:

```
git submodule update --init --recursive
```

To retreive the latest updates to the plugin, assuming you're using the "master" branch, go to "app/Plugin/Migrations" and run the following command:

```
git pull origin master
```

If you're using another branch, just change "master" for the branch you are currently using.

If any updates are added, go back to the base of your own repository, commit and push your changes. This will update your repository to point to the latest updates to the plugin.

Composer
--------

To install the plugin with the [Composer dependency manager](https://getcomposer.org/), run the following from your CakePHP project's ROOT directory (where the ``composer.json`` file is located):

```
php composer.phar require cakedc/migrations "~2.4.0"
```
