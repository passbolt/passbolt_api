@property {Object} System.buildConfig
@parent StealJS.config

An object of [System.config] overwrites to use when loading plugins while building.

@body

## Use

In an application's build process, it may be necessary to overwrite existing configuration properties, such as paths or maps. For example, a jQuery based application may have a configuration such as:

```
System.config({
  map: {
    "can/util/util": "can/util/jquery/jquery"
  }
});
```
However, during the build, DOM access may not be available. Altering the configuration as follows will be necessary:

```
System.config({
  buildConfig: {
    map: {
      "can/util/util": "can/util/domless/domless"
    }
  }
};
```
