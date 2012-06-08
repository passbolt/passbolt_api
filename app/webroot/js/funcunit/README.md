Relevant links:
1. http://jupiterit.com/#news/funcunit-fun-web-application-testing
2. http://groups.google.com/group/funcunit
3. http://twitter.com/funcunit
4. http://funcunit.com/


*Building a new FuncUnit*

Run funcunit/build.js again (to copy the jars).

That's it, funcunit/dist contains the same standalone funcunit placed into the standalone download.

*Organization*

 * qunit/print.js - methods to print qunit events (shared across qunit and funcunit)
 * qunit/selenium.js - collects items in the queue for selenium
 * qunit/envjs.js - listens to events and calls methods in print.js
 * qunit/qunit.js - QUnit
