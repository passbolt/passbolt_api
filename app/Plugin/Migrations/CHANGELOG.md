Changelog
=========

Release 2.4.1
-------------

* Bugfix/Improvement release

Release 2.4.0
-------------

* [43228df](https://github.com/cakedc/migrations/commit/43228df) Code standard fix
* [4352ed8](https://github.com/cakedc/migrations/commit/4352ed8) Changed method comment to a clearer comment
* [25d7181](https://github.com/cakedc/migrations/commit/25d7181) Changed uses of the ConnectionManager class to static
* [9a56814](https://github.com/cakedc/migrations/commit/9a56814) Fixed method comments
* [2ee4c1e](https://github.com/cakedc/migrations/commit/2ee4c1e) Changed call of enumConnectionObjects to static
* [caf1730](https://github.com/cakedc/migrations/commit/caf1730) Correct definition of the connection manager class uses
* [193ed70](https://github.com/cakedc/migrations/commit/193ed70) Fixed code formating
* [283590a](https://github.com/cakedc/migrations/commit/283590a) Fixed console input message that asks migrationConnection
* [1909320](https://github.com/cakedc/migrations/commit/1909320) Added test case to check migrationConnection shell parameter
* [b60fa05](https://github.com/cakedc/migrations/commit/b60fa05) Fixed array syntax in model uses
* [9237949](https://github.com/cakedc/migrations/commit/9237949) Validate migrateConnection argument when a custom connection is set
* [6374acc](https://github.com/cakedc/migrations/commit/6374acc) Added example to set a specific datasource on run

Release 2.3.6
-------------

* [1489496](https://github.com/cakedc/migrations/commit/1489496) Revert "fixing unit tests" to pass tests in travis env
* [ebf6bc6](https://github.com/cakedc/migrations/commit/ebf6bc6) using minor for travis CakePHP
* [517a810](https://github.com/cakedc/migrations/commit/517a810) updating travis
* [180fe03](https://github.com/cakedc/migrations/commit/180fe03) fixed class condition after some changes done in Inflector::camelize in 2.6.6
* [27d5afb](https://github.com/cakedc/migrations/commit/27d5afb) fixing unit tests

Release 2.3.6
-------------

https://github.com/CakeDC/migrations/tree/2.3.6

* [ccac5a3](https://github.com/cakedc/migrations/commit/ccac5a3) Update translation files
* [bca17ea](https://github.com/cakedc/migrations/commit/bca17ea) Show prompt for marking as successful when failure
* [18aa020](https://github.com/cakedc/migrations/commit/18aa020) crlf to lf
* [db96c9e](https://github.com/cakedc/migrations/commit/db96c9e) Grammatical corrections for generate command
* [cc7b03a](https://github.com/cakedc/migrations/commit/cc7b03a) Fix CS issues
* [942eab0](https://github.com/cakedc/migrations/commit/942eab0) Fix grammar in console output
* [89ddfc1](https://github.com/cakedc/migrations/commit/89ddfc1) Tidy up unlinking in tests
* [894f736](https://github.com/cakedc/migrations/commit/894f736) Fix for incorrect naming of all plugin migrations

Release 2.3.5
-------------

https://github.com/CakeDC/migrations/tree/2.3.5

* [69e6136](https://github.com/cakedc/migrations/commit/69e6136) Add translations for new/missing strings
* [c98ecdd](https://github.com/cakedc/migrations/commit/c98ecdd) Exit shell if comparing schema.php and nothing has changed

Release 2.3.4
-------------

https://github.com/CakeDC/migrations/tree/2.3.4

* [94a7fe9](https://github.com/cakedc/migrations/commit/94a7fe9) Removed cakephp dependency from composer.json

Release 2.3.3
-------------

https://github.com/CakeDC/migrations/tree/2.3.3

* [14a3cc4](https://github.com/cakedc/migrations/commit/14a3cc4) Bump minimum required CakePHP version to 2.5.4 (refs [#184](https://github.com/CakeDC/migrations/issues/184))
* [f6f3490](https://github.com/cakedc/migrations/commit/f6f3490) CS: Changed doc block "boolean" to "bool"
* [b6c579c](https://github.com/cakedc/migrations/commit/b6c579c) Fixes Schema/app.php issue.
* [749e634](https://github.com/cakedc/migrations/commit/749e634) Improved logic for schema class name detection.
* [9ef51fd](https://github.com/cakedc/migrations/commit/9ef51fd) Adds an option for specifying the Schema class name.

Release 2.3.2
-------------

https://github.com/CakeDC/migrations/tree/2.3.2

* [d3a3af7](https://github.com/cakedc/migrations/commit/d3a3af7) Fix CS in generated migrations (remove comma from param name in docblock)
* [abc4c92](https://github.com/cakedc/migrations/commit/abc4c92) Fix CS in generated migrations (remove trailing comma from drop_field array)
* [c952119](https://github.com/cakedc/migrations/commit/c952119) Fix migration generation when only indexes are changed (closes [#189](https://github.com/CakeDC/migrations/issues/189))
* [6386cd0](https://github.com/cakedc/migrations/commit/6386cd0) Removed else clause
* [735401f](https://github.com/cakedc/migrations/commit/735401f) Added preview description when generating preview migration class
* [4526828](https://github.com/cakedc/migrations/commit/4526828) Output entered description into $description in generated migration class
* [4ffb6bf](https://github.com/cakedc/migrations/commit/4ffb6bf) Removed redundant and seemingly superfluous migration template
* [ca982f6](https://github.com/cakedc/migrations/commit/ca982f6) Refs [#140](https://github.com/CakeDC/migrations/issues/140) Fixing the dry run output in other places as well
* [d6cc1fa](https://github.com/cakedc/migrations/commit/d6cc1fa) Fixing [#140](https://github.com/CakeDC/migrations/issues/140) Migration run dry is not equal to run in real
* [dcdbb20](https://github.com/cakedc/migrations/commit/dcdbb20) Update CHANGELOG.md
* [cca5544](https://github.com/cakedc/migrations/commit/cca5544) Removed disabled translations in PO files
* [72c3abc](https://github.com/cakedc/migrations/commit/72c3abc) Removed superfluous translation call
* [409de07](https://github.com/cakedc/migrations/commit/409de07) Added .idea to .gitignore
* [7de581c](https://github.com/cakedc/migrations/commit/7de581c) Set up some .gitignore rules
* [c31eca8](https://github.com/cakedc/migrations/commit/c31eca8) Updated PO file headers from POT file
* [d9a7ea3](https://github.com/cakedc/migrations/commit/d9a7ea3) Improved POT file header
* [92158ab](https://github.com/cakedc/migrations/commit/92158ab) Updated spa locale from POT file
* [4554b23](https://github.com/cakedc/migrations/commit/4554b23) Updated por locale from POT file
* [7b9d383](https://github.com/cakedc/migrations/commit/7b9d383) Updated ita locale from POT file
* [5acffda](https://github.com/cakedc/migrations/commit/5acffda) Updated fre locale from POT file
* [af97e06](https://github.com/cakedc/migrations/commit/af97e06) Updated deu locale from POT file
* [a33853c](https://github.com/cakedc/migrations/commit/a33853c) Regenerated migrations.pot file
* [e830bc3](https://github.com/cakedc/migrations/commit/e830bc3) Made the migration shell messages more clear by adding quotes and the word table
* [bc4bbe6](https://github.com/cakedc/migrations/commit/bc4bbe6) Fixing the class name generation in MigrationShell::_getSchema()

Release 2.3.1
-------------

https://github.com/CakeDC/migrations/tree/2.3.1

* [117e958](https://github.com/cakedc/migrations/commit/117e958) Updating the Installation.md
* [71acf74](https://github.com/cakedc/migrations/commit/71acf74) Adding in the load statement to be added in to bootstrap
* [a1467a5](https://github.com/cakedc/migrations/commit/a1467a5) Fix the doc blocks

Release 2.3.0
-------------

https://github.com/CakeDC/migrations/tree/2.3.0

* [12c0f80](https://github.com/CakeDC/migrations/commit/12c0f80) Updating the required CakePHP version in the documentation
* [3f1da70](https://github.com/CakeDC/migrations/commit/3f1da70) Adding documentation for the columns
* [0d2a76c](https://github.com/CakeDC/migrations/commit/0d2a76c) Updating a test file
* [7dec605](https://github.com/CakeDC/migrations/commit/7dec605) Fix reversal of Hash::extract and Set::extract arguments (woops)
* [2e48fd5](https://github.com/CakeDC/migrations/commit/2e48fd5) Updating Travis
* [0244df4](https://github.com/CakeDC/migrations/commit/0244df4) Adding CONTRIBUTING.md
* [8c0c9d8](https://github.com/CakeDC/migrations/commit/8c0c9d8) Update deprecated Set calls to use Hash
* [d548d3e](https://github.com/CakeDC/migrations/commit/d548d3e) Updating the documentation for generating migrations via CLI without DB
* [4bdc354](https://github.com/CakeDC/migrations/commit/4bdc354) Updating the documentation for creating migrations interactive via CLI
* [838a106](https://github.com/CakeDC/migrations/commit/838a106) Adding documentation for the new generate migrations without DB interaction via CLI feature
* [c52d7cb](https://github.com/CakeDC/migrations/commit/c52d7cb) Simplifying MigrationShellTest::testMigrationStatus
* [f8f4acc](https://github.com/CakeDC/migrations/commit/f8f4acc) Fixing testGenerateComparison
* [70fa569](https://github.com/CakeDC/migrations/commit/70fa569) Adjust database setup in travis.yml
* [7c89dae](https://github.com/CakeDC/migrations/commit/7c89dae) Update travis.yml to test with php 5.5 and more cake versions
* [93f5943](https://github.com/CakeDC/migrations/commit/93f5943) Implement code & test for 'add fields' from cli arguments
* [76bb49c](https://github.com/CakeDC/migrations/commit/76bb49c) Implement code & test for 'remove fields' from cli arguments
* [c577686](https://github.com/CakeDC/migrations/commit/c577686) Refactor & implement test for 'create table' and 'drop table' from cli arguments
* [0baf125](https://github.com/CakeDC/migrations/commit/0baf125) Fix whitespace coding standards
* [491a14b](https://github.com/CakeDC/migrations/commit/491a14b) Refactor - break up 80+ line method
* [28d7eae](https://github.com/CakeDC/migrations/commit/28d7eae) Fix failing test - testRun
* [ebb794c](https://github.com/CakeDC/migrations/commit/ebb794c) Fix failing test - testGenerateDump
* [14cdc86](https://github.com/CakeDC/migrations/commit/14cdc86) Add ability to generate migration files without db interaction from CLI
* [c52d7cb](https://github.com/CakeDC/migrations/commit/c52d7cb) Simplifying MigrationShellTest::testMigrationStatus
* [f8f4acc](https://github.com/CakeDC/migrations/commit/f8f4acc) Fixing testGenerateComparison
* [fb154ba](https://github.com/CakeDC/migrations/commit/fb154ba) Updating deprecated phpunit method names
* [891d531](https://github.com/CakeDC/migrations/commit/891d531) Updating deprecated phpunit method calls
* [8745815](https://github.com/CakeDC/migrations/commit/8745815) Fixing MigrationShellTest::testGenerateDump()
* [22e8e75](https://github.com/CakeDC/migrations/commit/22e8e75) Fixing MigrationVersionTest::testRun()
* [6f2e0ef](https://github.com/CakeDC/migrations/commit/6f2e0ef) Update MigrationShell.php
* [8995d27](https://github.com/CakeDC/migrations/commit/8995d27) Prevent undefined index error rather than masking the actual error
* [c4bfe0c](https://github.com/CakeDC/migrations/commit/c4bfe0c) Changing the test_migration.txt file to reflect the changes in the migration generation
* [2095306](https://github.com/CakeDC/migrations/commit/2095306) Fixing tests, removed a return; where it should not be and corrected an assert
* [d772fc7](https://github.com/CakeDC/migrations/commit/d772fc7) Fix broken test in Model CakeMigrationTest. An exception is thrown when you try to create an index withf231417 fix setUp for tests and assert english as language
* [830fb76](https://github.com/CakeDC/migrations/commit/830fb76) Coding standards
* [4c79e60](https://github.com/CakeDC/migrations/commit/4c79e60) Remove unnecessary code
* [c745fe0](https://github.com/CakeDC/migrations/commit/c745fe0) Cleanup and coding standards
* [58aa7ee](https://github.com/CakeDC/migrations/commit/58aa7ee) Removing legacy reset/restore methods and array key
