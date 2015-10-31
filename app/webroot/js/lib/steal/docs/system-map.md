@property {Object.<moduleGlob, moduleName|Object.<moduleGlob,moduleName>>} System.map
@parent StealJS.config

Alter [moduleName] keys.

@option {Object.<moduleGlob, moduleName|Object.<moduleGlob,moduleName>>} 

Specifies rules to convert an imported moduleName to another module name. The rules can
be specified globally or limited to a specific path.

The following will alter "glob/*" modules across the whole application.

    System.map["glob/*"] = "moduleName/*" 

The following limits converting "jquery" to "jquery@1.2" to only within modules that match
"oldcode/*":

    System.map["oldcode/*"] = {
      "jquery": "jquery@1.2"
    };

@body

## Uses

Map is useful when you want to exchange one module for another.

### Alternative implementation

There are many libraries that share a common API. Consider a legacy application 
that heavily used Underscore. You might want to migrate to Lodash for added 
features or performance reasons. You can use map to do this without updating all of 
your code that uses Underscore like so:

    System.map.underscore = "lodash";

This would save you from updating every module that had previously imported Underscore,
however in some cases you are unable to update the modules in the first place  
because they are third party libraries. Consider a MVC library that has a dependency
on jQuery. If you wanted to use the smaller alternative Zepto you could simply
map `jquery` to `zepto` and the MVC library would use that instead.

### Ignoring optional dependencies

Some modules might have dependencies on other modules that they only use as an option
if you need them. Because there isn't a standard way to define conditional dependencies
they likely just import them explicitly. If you do not need this option you can
elect to ignore the dependency by mapping it to `@empty`:

    System.map["some/optional_dep"] = "@empty";

`@empty` is a pseudo-module defined by SystemJS to represent a module with no value.

## Implementation

Implemented by [SystemJS](https://github.com/systemjs/systemjs#map-configuration). 
