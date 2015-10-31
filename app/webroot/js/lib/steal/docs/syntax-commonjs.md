@function syntax.CommonJS CommonJS 
@parent StealJS.syntaxes

@signature `require(moduleName)`

The [CommonJS](http://wiki.commonjs.org/wiki/CommonJS) syntax used commonly in 
NodeJS environments.

@param {String} moduleName The name of module to load.

@return {*} The module value.

@body

## Use

CommonJS is a popular format used in Node.js, but has also caught on in the browser. People 
like CommonJS because it doesn't require a wrapper function. You might define a module like so:
```
var can = require("can");
var _ = require("underscore");
var myModule = require("some_module/some_module");

module.exports = can.Component.extend({

});
```
With CommonJS a single file will always define a single module. In includes 3 key objects: `require`, `exports` and `module`.

**require** is used to import modules as dependencies. In the above example, `can` is being imported using require.

**exports** is an object that can be used to attach properties to the exported module definition.

**module** is an object representing the module definition. You'll often use either `exports` or `module`. 
Usually module is used when you want to export a single value, like a function.