Generate Migrations Without DB Interaction
==========================================

This functionality only works when arguments are passed to the command `cake Migrations.migration generate`.

This commit adds the ability to perform commands such as the following:

```
app/Console/cake Migrations.migration generate create_users id:primary_key name:string created modified
```

```
app/Console/cake Migrations.migration generate alter_users name:string:index
```

```
app/Console/cake Migrations.migration generate drop_users
```

```
app/Console/cake Migrations.migration generate add_taxonomic_stuff_to_posts category:string tags:string
```

```
app/Console/cake Migrations.migration generate remove_taxonomic_stuff_from_posts category tags
```

The above commands would:

* Create a users table with the fields ["id", "name", "created", "modified"]. A single primary key index would exist on "id", and the "created" and "modified" fields would default to "datetime", as per *CakePHP* conventions. Since the type is specified on "name", it is a string.
* Add an index to the "name" column in the "users" table.
* Drop the users table.
* Add "category" and "tags" fields to the "posts" table.
* Remove "category" and "tags" fields from the "posts" table.

Due to the conventions, not all schema changes can be performed via these shell commands.

Migration names can follow any of the following regex patterns:

* **create_table** `/^(create)_(.*)/`: Creates the specified table.
* **drop_table** `/^(drop)_(.*)/`: Drops the specified table. Ignores specified field arguments.
* **add_field** `/^(add)_.*_(?:to)_(.*)/`: Adds fields to the specified table.
* **remove_field** `/^(remove)_.*_(?:from)_(.*)/`: Removes fields from the specified table.
* **alter_table** `/^(alter)_(.*)/` : Alters the specified table. The **alter_table** command can be used as an alias for **create_table** and **add_field**.

Migration names are used as migration class names, and thus may collide with other migrations if the class names are not unique. In this case, it may be necessary to manually override the name at a later date, or simply change the name you are specifying.

Fields are verified via the following regular expression:

```
/^(\w*)(?::(\w*))?(?::(\w*))?(?::(\w*))?/
```

These follow the format:

```
field:fieldType:indexType:indexName
```

For instance, the following are all valid ways of specifying the primary key "id":

* `id:primary_key`
* `id:primary_key:primary`
* `id:integer:primary`
* `id:integer:primary:ID_INDEX`

Field types are specific to the database connection in use specified by the `--connection` argument ("default" by default). Thus, valid **Postgres** field types include "inet" and "number", while for **MySQL** the fieldType would be ignored.

There are some heuristics to choosing field types when left unspecified or set to an invalid value:

* **id:** integer
* **created / modified / updated:** datetime
* **default:** string

Lengths for certain columns are also defaulted:

* **string:** 255
* **integer:** 11
* **biginteger:** 20