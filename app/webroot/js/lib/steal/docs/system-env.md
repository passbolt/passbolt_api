@property {String} System.env
@parent StealJS.config

Specifies which environment the application is loading within. 

@option {String} Possible values
are `"development"` and `"production"`.  Defaults to `"development"`.

@body

## Use

Setting `env` to production mode is a short cut to prevent steal from loading the
[@config] and [@dev] modules and make steal load the [System.main] module
in a bundle.

For example:

```
System.config({
  main: "myapp",
  env: "production"
});
```

Sets:

```
System.bundles["bundles/myapp"] //-> ["myapp"]
System.meta["bundles/myapp"]    //-> {format: "amd"}
System.paths["bundles/*"]       //-> "dist/bundles/*.js"
System.paths["bundles/*.css"]   //-> "dist/bundles/*.css"
```

Setting `System.env` to `"production"` must happen prior to loading `steal.js`.  So it should
be [System.config configured] via the `steal.js` script tag like:

```
<script src="../path/to/steal/steal.js"
        data-env="production"
        data-main="myapp">
</script>
```

Or specified prior to steal loading like:

```
<script>
  steal = {env: "production"}
</script>
<script src="../path/to/steal/steal.js"
        data-env="production">
</script>
```
