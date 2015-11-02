@property {function} System.config
@parent StealJS.config
@alias steal.config

Specifies configuration values on System. This should be used to
set properties like [System.configPath] and [System.env].

@param {Object} config An object of configuration values.

@body

## Use

`System.config` can be called in three ways.  

### Programatically

Call `System.config` after _steal.js_ has been loaded like:

    System.config({
      paths: { ... },
      map: { ... }
    });

This is is most commonly done in the [@config] module.

### Script Attributes

Any property besides src, id, and type will be used to set on System:

    <script src="../path/to/steal/steal.js"
            config-path="../path/to/stealconfig.js"
            main="app">
    </script>

### steal object

A `steal` object loaded before `steal.js` will be used as a System.config argument.

    <script>
      var steal = {
        configPath: "../path/to/stealconfig.js",
        main: "app"
      }
    </script>
    <script src="../path/to/steal/steal.js"></script>


## Implementation

Basic deep merging of configuration properties is done in [SystemJS](https://github.com/bitovi/systemjs).

Side effect property setting is done by steal.
