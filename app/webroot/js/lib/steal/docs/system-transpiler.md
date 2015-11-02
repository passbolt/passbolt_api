@property {String} System.transpiler
@parent StealJS.config

Specifies which transpiler to use for ES6 modules. Traceur has been around for a longer time, but Babel provides advantages such as a smaller overhead.

@option {String=traceur} Which ES6 compiler to user to generate ES5 code. Possible values:

* `traceur`: The default, uses [traceur-compiler](https://github.com/google/traceur-compiler).
* `babel`: Uses [Babel](https://babeljs.io/).

@body

## Use

If you'd like to control which ES6 transpiler is used simply set in your config:

    System.config({
      transpiler: "babel"
    });

## Implementation

Provided by [ES6 Module Loader](https://github.com/ModuleLoader/es6-module-loader).
