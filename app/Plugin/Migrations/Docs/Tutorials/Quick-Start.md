Quick Start
===========

This quick start guide will help you get ready to use the **Migrations** plugin with your application.

Usage
-----

Run ```Console/cake Migrations.migration run all -p Migrations``` to initialize the ```schema_migrations``` table.

Generating your First Migration
-------------------------------

The first step to adding migrations to an existing database is to import the database's structure into a format the **Migrations** plugin can work with. Namely, a migration file. To create your *first* migration file run the following command:

```
cake Migrations.migration generate
```

Follow the script and answer the questions. This will generate a new file containing a database structure snapshot using the internal **Migrations** plugin syntax. If you want to import all of your tables, regardless if it has a model or not, you can use ```-f``` (force) parameter while running the command.

```
cake Migrations.migration generate -f
```

Running Migrations
------------------

After generating or being supplied with a set of migrations, you can process them to change the state of your database.

This is the crux of the **Migrations** plugin, allowing the migration of schemas up and down the migration chain,
offering flexibility and easy management of your schema and data states.

This will run the next not yet applied migration:

```
cake Migrations.migration run up
```

This will revert the last applied migration:

```
cake Migrations.migration run down
```

This will run all migrations that are found:

```
cake Migrations.migration run all
```