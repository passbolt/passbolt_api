@property {function} steal.import
@parent StealJS.functions

Dynamically imports modules after initial configuration has loaded. Otherwise works very similar to [System.import].

@signature `steal.import(...moduleName)`

@param {moduleName} moduleName Names of modules wanting to import.

@return {Promise} A promise that will resolve with the values of the imported modules.

@body

## Use

`steal.import` is useful for dynamically importing modules. It is like [System.import] but with 2 advantages:

1. It waits for the [System.configMain] to be loaded before importing `moduleName`, so if configuration is needed it will work.
2. It allows you to pass multiple moduleNames as arguments to prevent verbose code.

### Browser

```js
<script src="node_modules/steal/steal.js"></script>
<script>
	steal.import("my/main").then(function(mainValue){
		
	});
</script>
```

### Node

`steal.import` is useful in Node for the same reason, as it prevents having to call `steal.startup` prior to importing the modules you care about.

```js
var steal = require("steal").clone();

steal.import("server/main");
```
