@function syntax.global global 
@parent StealJS.syntaxes

If a script simply writes exports its values on the global or window object, 
it uses the "global" syntax.

@body

## Use

A global module might look like:

    // app/sample-global.js
    hello = "world";
     
Use [System.meta] to configure this module as the global format like:

    System.meta["app/sample-global"] = {format: "global"}

Using [System.meta] you can also set a module's dependencies and what it exports.  These configurations 
can also be set inline like:

    // app/sample-global.js
    "format global";
    "exports hello";
    hello = "world";

## Implementation 

Implemented in [SystemJS](https://github.com/systemjs/systemjs#global-module-format-support)
