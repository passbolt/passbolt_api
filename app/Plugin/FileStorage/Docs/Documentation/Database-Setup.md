Database Setup
==============

You need to setup the plugin database using either the [CakePHPs built in schema shell](http://book.cakephp.org/2.0/en/console-and-shells/schema-management-and-migrations.html)

	cake schema create --plugin FileStorage

or the [CakeDC Migrations plugin](http://github.com/CakeDC/migrations).

	cake Migrations.migration run all --plugin FileStorage

Integer type IDs vs UUIDs
-------------------------

If you want to use integers instead of [UUIDs](http://en.wikipedia.org/wiki/Universally_unique_identifier) put this into your ```bootstrap.php``` *before* you're running the migrations:

	Configure::write('FileStorage.schema.useIntegers', true);

This config option is **not** available for the regular CakePHP schema that comes with the plugin. It seems to be impossible to override the type on the fly. If you can figure out how to do it a pull request is welcome.
