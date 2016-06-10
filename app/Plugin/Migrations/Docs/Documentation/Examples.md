Examples
========

Generate a Migration File
-------------------------

Creating a new migration file.

```
cake Migrations.migration generate
```

If you want import all tables regardless if it has a model or not you can use ```-f``` (force) parameter.

```
cake Migrations.migration generate -f
```

Running All Pending Migrations
-----------------------------

Get all pending changes into your database run.

```
cake Migrations.migration run all
```

Resetting Your Database
-----------------------

Reset your database to the initial state of your first migration run.

```
cake Migrations.migration run reset
```

Downgrade to Previous Version
-----------------------------

Downgrades your database to the previous migration.

```
cake Migrations.migration run down
```

Upgrade to Next Version
-----------------------

Applies the next migration to your database.

```
cake Migrations.migration run up
```

Running Migrations for Plugins
------------------------------

Running a migration of a plugin you can use the plugin option.

```
cake Migrations.migration run all --plugin Users
```

Getting the Status of Available/Applied Migrations
--------------------------------------------------

Gets the status of all migrations.

```
cake Migrations.migration status
```

Adding a specific datasource
--------------------------------------------------

Will set the datasource and will run the migrations in this datasource.

```
cake Migrations.migration run --connection my_data_source
```