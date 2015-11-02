@property {String} System.bundlesPath
@parent StealJS.config

A convience configuration property for setting the path of where the [System.env production] 
bundles folder is located.

@option {String} 

A folder name that specifies the path to the production bundles.  By default,
`System.bundlesPath` is `"dist/bundles"`.  

@body

## Use

In [System.env production], the [System.main] module will be assumed to be within a 
_"bundles/[MAIN\_MODULE\_NAME]"_ module. For example, if the main module is `myapp`,
a `bundles/myapp` module is automatically configured to contain it:

```
<script src="steal/steal.js"
        config="./config.js"
        main="myapp"
        env="production">
</script>
<script>
  System.bundles["bundles/myapp"] //-> ["myapp"]
</script>
```

`System.bundlesPath` tells the client where all bundles can be found by configuring
[System.paths]. For example, if bundlesPath is set to `packages`:

```
<script src="steal/steal.js"
        config="./config.js"
        main="myapp"
        env="production"
        bundles-path="packages">
</script>
<script>
  System.bundles["bundles/myapp"] //-> ["myapp"]
  System.paths["bundles/*"] = "packages/*.js";
  System.paths["bundles/*.css"] = "packages/*.css";
</script>
```

Often, `bundlesPath` should be the same value as what's passed in [steal-tools.build]. If
`bundlesPath` is not set, it will set the default bundles paths:

```
<script src="steal/steal.js"
        config="./config.js"
        main="myapp"
        env="production"
        bundles-path="packages">
</script>
<script>
  System.bundles["bundles/myapp"] //-> ["myapp"]
  System.paths["bundles/*"] = "dist/bundles/*.js";
  System.paths["bundles/*.css"] = "dist/bundles/*.css";
</script>
```

If a path rule for `System.paths["bundles/*"]` or `System.paths["bundles/*.css"]`
exist, `bundlesPath` will not overwrite them.

