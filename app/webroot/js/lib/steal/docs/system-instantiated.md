@property {Object<moduleName,*>} System.instantiated
@parent StealJS.config

Specify modules that have already been loaded prior to loading `steal.js`.

@option {Object<moduleName,*>} An object of moduleName keys to 
module values.  All values will be converted into a module.

@body

## Use

This config should be set before `steal.js` is loaded.  The following
prevents the production bundle from loading:

```
<script>
  steal = {
    instantiated: {
      "bundles/myapp.css!$css" : null
    }
  }
</script>
<script src="../../steal/steal.js"
        main="myapp"
        env="production">
</script>
```
