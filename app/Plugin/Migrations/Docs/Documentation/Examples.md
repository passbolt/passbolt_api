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

If you want to force the comparison between schema file and database you can use ```--compare``` parameter.

```
cake Migrations.migration generate --compare
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

Skipping certain migrations
--------------------------------------------------

Will skip one migration.

```
cake Migrations.migration run all --skip 1458963215_articles_table
```

You can skip many migrations using comma to separate, for example.

```
cake Migrations.migration run all --skip 1458963215_articles_table,1457412585_users_table
```

Remember this migrations will be set as executed.

Jumping to certain migrations
--------------------------------------------------

If you want to jump to a certain migration, you can use ```--jump-to``` or ```-j``` + migration name as in the example below.

```
cake Migrations.migration run all -j 1458963215_articles_table
```

Remember all migrations before this will be set as executed.