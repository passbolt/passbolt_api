@property {Object<moduleName, Array.<moduleName>>} System.bundles
@parent StealJS.config

Bundles configuration allows a single bundle file to be loaded in place of separate module files.

@option {Object<moduleName, Array.<moduleName>>} An object where keys
are bundle [moduleName moduleNames] and values are Arrays of [moduleName moduleNames] that
the bundle contains.


@body

## Use

Specify `bundles` if you are using a prebuilt bundle. For example, if `"jqueryui.autocomplete"` 
and `"jqueryui.datepicker"` are in `"jqueryui.custom"`, you can specify that like:

```
System.bundles["jqueryui.custom"] = [
  "jqueryui.autocomplete",
  "jqueryui.datepicker"
];
```

If `bundle` is passed to [steal-tools], it will write out where to load bundles in the production bundles. 

## Production Default Values

In [System.env production] a bundles is written out to 
contain the [System.main] module.  For example:

```
System.config({
  main: "myapp",
  env: "production"
});
System.bundles["bundles/myapp"] = ["myapp"]
```

This way, when the `"myapp"` module is imported, System will load ["bundles/myapp"].  Use [System.bundlesPath]
to configure where bundles are found.


## Implementation

Provided by [SystemJS](https://github.com/systemjs/systemjs#bundles).