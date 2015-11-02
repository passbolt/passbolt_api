@property {function} System.import
@parent StealJS.functions

Dynamically import modules from any location into your application.

@param {String} moduleName The [moduleName] of the module you want to load.

@return {Promise} A promise that will resolve with the value of the module. It will resolve once the module and all of the module's dependencies have been fully resolved and executed.

@body

## Use

`System.import` is used to dynamically import a module. This is counter to the syntaxes which are statically parsed and dependencies loaded prior to running the code. In the following example we are importing the "lodash" module and assigning it's value to the `_` variable in the Promise callback function.

    System.import("lodash").then(function(_) {
      // Use lodash
      _.isString("hello world"); // -> true
    });

### When to use

Typically you won't need to use `System.import` very often in your application. There are two primary cases when you might want to use it:

1. Load a module whose [moduleName] is _determined at runtime_. If you need to concat a string in order to determine which module to load, `System.import` is a good candidate:

        System.import("browser-hacks/" + browserId).then(function() {
          // Browser workaround code loaded.
        });

2. For use with progressive loading. If you use [System.bundle] in your application to determine bundles that will be progressively loaded, you use `System.import` to load a particular bundle. For example, if one of your bundles is `checkout` you would load that dynamically with `System.import("checkout")`.
