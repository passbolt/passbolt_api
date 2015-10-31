@function syntax.steal steal 
@parent StealJS.syntaxes

@body

## Use

If you used the old Steal you're already familiar with this syntax, 
and it works the same in the new version. List all of your dependencies as regular 
arguments to `steal` and the last argument is a function to be called to define the module's 
value after all of the dependencies have been loaded:

    steal("can", "underscore", "some_module", function(can, _, myModule){
      return can.Component.extend({
       
      });
    });

Steal differs from other syntaxes in one key way, when specifying dependencies, 
the module ids point to a folder with the pattern of `folder/folder.js`. 
In the above example `can` will look for the module at `can/can.js`.
