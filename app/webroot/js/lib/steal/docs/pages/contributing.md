@page guides.Contributing Contributing
@parent StealJS.guides

Thank you for contributing! 

## Bugs and Feature Requests

Bugs and feature requests should be submitted to 
[steal](http://github.com/bitovi/steal/issues/new) for issues
with module loading and [steal-tools](http://github.com/bitovi/steal-tools/issues/new)
for issues building.  

The best issues are those submitted with tests.  Learn about how 
to setup a test in the "Developing" sections below.

## Developing steal

To develop steal, fork and clone [steal](http://github.com/bitovi/steal). Make
sure you have NodeJS installed. Then:

1.  Install npm modules
  
        > npm install
 
2. Install bower modules
  
        > bower install
    
3. Setup grunt watch
  
        > grunt watch
    
This will automatically build `steal.js` and `steal.production.js`
when anything in `src` changes.  
  
To __test__, open `test/test.html`, and make sure everything passes.

## Understanding steal's code

`steal.js` packages two other projects:

 - [ES6ModuleLoader](https://github.com/ModuleLoader/es6-module-loader) - Provides the [Loader](steal#section_LoaderandSystemnamespaces) and [System](steal#section_LoaderandSystemnamespaces) Polyfill.
 - [SystemJS](https://github.com/systemjs/systemjs) - Provides most System extensions 
   like [System.paths], [System.map] and the [syntax.amd AMD] and [syntax.CommonJS CommonJS] syntaxes.

And `steal.js` includes everything in the `src` folder.  On a high level, steal.js is
organized like:

    /* ES6ModuleLoader */
        /* - Promise polyfill */
    !function(e){"object"==typeof ...}
        /* - Loader Polyfill */
        /* - System Polyfill */
    
    /* SystemJS */
    (function(__$global) {
      /* meta, register, core, global, cjs, amd, map, 
         plugins, bundles, versions, depCache extensions */
    });
    
    /* start.js
     * - wraps everything in a closure
     * - helpers that are useful everywhere
     */
    (function(global){
        // helpers
        var camelize, each, map, isString, extend, parseURI;
    /* normalize.js
     * - standalone normalize function
     */
        var normalize = function(){}
    /* core.js
     * - starts `makeSteal` that makes `steal`.
     */
        var makeSteal = function( System ){
            var steal = function(){ ... }
    /* system-extension-ext.js
     * - the System.ext extension
     */
            var addExt = function(loader) { .. };
            addExt(System);
    /* system-extension-forward-slash.js
     * - module names that end in /
     */
            var addForwardSlash = function(loader) { ... };
            addForwardSlash(System);
    /* config.js
     * - overwrites System.config
     * - sets up System.env, System.stealPath, System.bundlesPath
     */
            var setterConfig = function(...){...}
            setterConfig(System,{ ... });
    /* startup.js
     * - defines startup code
     */
            var getScriptOptions = function () { ... }
            steal.startup = function(config){ ... }
    /* make-steal-end.js
     * - closes `makeSteal`
     */
        };
    /* system-format-steal.js
     * - a System extension that adds the `steal` syntax
     */
        function addSteal(loader) { ... }
        addSteal(System);
    /* end.js
     * - 
     */
        if (typeof window != 'undefined') {
            window.steal = makeSteal(System);
            window.steal.startup( oldSteal );
            ...
        } else {
            require('systemjs');
            global.steal = makeSteal(System);
            ...
        }
    })(typeof window == "undefined" ? global : window);

## Writing a steal test

All tests are in `test/test.js`.  Most tests
create an iframe that opens another page like:

    asyncTest("test description", function(){
        makeIframe("path/to/page.html");
    });

That page typically uses steal to import some 
modules and when complete calls to the parent window's
QUnit assertion methods.  For example:

    <!-- page.html -->
    <html>
       <script src='../../steal/steal.js'
               main='test-module'
    </html>

    //test-module.js
    window.parent.QUnit.ok(true, "test-module-loaded");
    window.parent.QUnit.start();

## Developing steal-tools

To develop steal, fork and clone [steal-tools](http://github.com/bitovi/steal-tools). Make
sure you have NodeJS installed. Then:

1.  Install npm modules
  
        > npm install
 
2. Install bower modules
  
        > bower install
  
3. Install mocha

        > npm install -g mocha


To __test__, run:

        > mocha test/test.js

### Windows

Note that if you are using Windows you first need to install a couple of things before
you `npm install`.

1. Install [Python 2.7](https://www.python.org/download/releases/2.7/). You'll want
to add the directory to your PATHS (likely `C:\Python27`).

2. Install [Microsoft Build Tools](http://www.microsoft.com/en-us/download/details.aspx?id=40760)
or [Visual Studio Express 2013](http://www.visualstudio.com/downloads/download-visual-studio-vs#d-express-windows-desktop)
depending on which version of Windows you are using. Try the Build Tools but
VSE might be needed.

## Understanding steal-tools code

On a high level, [steal-tools steal-tools] uses `lib/trace.js` to 
figure out all of the 
dependencies for a module and arange them into a graph. It
then transforms that graph into bundles and finally writes
out the bundles so they can be loaded by the client in production.

A dependency graph that `trace.js` produces looks like:

    {
      moduleName: Node({
        load: Load({
          name: moduleName,
          source: SOURCE
        }),
        dependencies: [moduleName,...],
        // which [System.bundle] moduleNames this module is a dependency of.
        bundles: [bundleName,...],
      })
    }

Here's an example:

    {
      "jquery": {
        load: {
          name: "jquery",
          source: "jQuery = function(){ ... }"
        },
        dependencies: [],
        bundles: ["profile","settings","login", ...]
      },
      "can/util": {
        load: {
          name: "can/util",
          source: "define(['jquery'], function($){ ... })" 
        },
        dependencies: ["jquery"],
        bundles: ["profile","login"]
      }
    }

A `Load` is a ES6 load record.

A `Node` is an object that contains the load and other 
useful information like the modules:

 - dependencies
 - bundles
 - transformed and/or minified source

The build tools only write to `Node` to keep `Load` from being changed.
They manipulate this graph and eventually creates "bundle" 
graphs.  Bundle graphs look like:

     {
       // How many bytes this bundle is minified
       size: 231231,
       // The Nodes (and therefore modules) this bundle contains
       nodes: [node1, node2, ...],
       // The [System.bundle] moduleNames this bundle is a dependency of
       bundles: [bundleName1, bundleName2]	
     }

[steal-tools steal-tools] code is organized in folders around certain concepts:

 - build - high level build modules like the multi build and pluginify.
 - buildTypes - utitlies for specific buildTypes (like CSS or JS).
 - bundle - transformations around bundle objects.
 - configuration - utility for getting derived configuration values.
 - graph - graph creation and transformation utilities.
 - node - utilities around a node in a graph

## Website and Documentation

[steal's gh-pages branch](https://github.com/bitovi/steal/tree/gh-pages)
contains [stealjs.com](http://stealjs.com)'s code. It 
uses [DocumentJS](http://github.com/bitovi/documentjs) to produce the 
website.  To edit the docs:

1. Fork/Clone https://github.com/bitovi/steal/tree/gh-pages:
  
        > git clone git@github.com:bitovi/steal -b gh-pages

2. Install NPM dependencies:

        > npm install
        
3. Install steal and steal-tools submodules:
  
        > git submodule init
        > git submodule update
        
4. Generate site

        > node build.js
        
5. Edit markdown files in `steal/docs` or `steal-tools/doc`
  
6. Regenerate site and check changes

        > ./node_modules/.bin/documentjs

7. Checkin and push markdown changes to steal or steal-tools.
8. Checkin and push gh-pages branch changes.  






