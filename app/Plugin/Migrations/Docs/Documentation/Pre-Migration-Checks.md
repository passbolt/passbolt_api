Pre-Migration Checks
====================

The migration system supports two checking modes: *exception-based* and *condition-based*.

The main difference is that exceptions will make the migration shell fail hard, while the *condition-based* check is a more graceful way to check for possible problems with a migration before exceptions even occur.

If the database has already applied some modifications and you try to execute the same migration again, then the migration system will throw an exception. This is the *exception-based* mode for the migrations system, which is the default mode.

The *condition-based* mode works differently. When the system is running a migration it checks that it's possible to apply the migration on the current database structure. For example, when creating a table, if it already exists, it will stop before applying the migration.

Another example is dropping a field, where the pre-migration check will detect if the table and field exists
and, if not, won't apply the migration.

To enable *condition-based* mode use ```--precheck Migrations.PrecheckCondition``` with the migration shell.

Customized Pre-Migration Checks
-------------------------------

It's also possible to implement custom pre-checks. Your custom pre-check class must extend the
[PrecheckBase](../../Lib/Migration/PrecheckBase.php) class from the plugin. You must also put your class in ```app/Lib/Migration/<YourClass>.php``` or inside a plugin.

To run your class use ```--precheck YourPrecheckClass```, or to load it from another plugin simply following the dot notation, for example ```--precheck YourPlugin.YourPrecheckClass```.

Migration Shell Return Codes
----------------------------

If you're scripting against the migration shell, it's useful to keep in mind the possible return codes:

* 0 = Success
* 1 = No migrations available
* 2 = Not a valid migration version
