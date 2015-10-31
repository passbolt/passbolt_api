@function syntax.amd AMD 
@parent StealJS.syntaxes

@body

## Use

AMD is similar to [syntax.steal steal] in that you define a module using a wrapper function. 
Unlike steal, modules are defined as an array, like so:

    define([
        "can/can", 
        "underscore/underscore", 
        "some_module/some_module"], 
      function(can, _, myModule){
        return can.Component.extend({

        });
    });

Also like Steal, with AMD you define the module's definition by returning a value from the function body. AMD differs in that the module ids you pass in the dependency array must point to a single file, not a folder.

AMD also provides a conventient syntax that can be used rather than providing a dependency array that mimics CommonJS:

    define(function(require, exports, module){
      var can = require("can/can");
      var _ = require("underscore/underscore");
      var myModule = require("my_module/my_module");

      return ...
    });
