@property {String} System.baseURL
@alias System.baseUrl
@parent StealJS.config

Specifies the root path to use for all module lookups. 

@option {String} The root path to use for all 
module lookups. If baseURL is not specified, the baseURL is treated
as the page's directory in a browser and `process.cwd()` in node.

Specifying [System.config] will set `baseURL` to the config's parent directory.


@body

## Use

When a module is imported, the `baseURL` will be the prepended to the module location by 
default. For example:

```
System.baseURL = "../libs";
System.import("mylib")      // looks in ../libs/mylib
```

This behavior can futher be modified by [System.paths]

## Implementation

Provided by [ES6 Module Loader](https://github.com/ModuleLoader/es6-module-loader#baseurl).