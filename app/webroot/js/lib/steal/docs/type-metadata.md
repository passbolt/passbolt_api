@typedef {{}} load.metadata metadata
@parent StealJS.types

An object that is passed between `Loader` hooks.  Any values can be set.  These are the ones that `steal.js` and
SystemJS recognize.

@option {String} format Specifies what type of Syntax is used.  This can be specified like:

    "format amd";

@option {Array.<moduleName>} deps Dependencies.

@option {String} exports What should be exported.

@option {function(this:Global,Module...)} init Allows for calling noConflict and
for other cleanup.  `this` will be the global object.  

@body

## Implementation

Implemented in SystemJS.