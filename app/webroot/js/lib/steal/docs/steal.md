@page steal
@parent StealJS.api
@group StealJS.syntaxes syntaxes
@group StealJS.config config
@group StealJS.modules modules
@group StealJS.types types
@group StealJS.functions functions

Steal is a  module loader that supports a wide variety of 
syntaxes and configuration options. It makes modular development, test
and production workflows simple.

There are four basic steps when using Steal:

 - Install steal
 - Add the steal script tag
 - Configure steal
 - Import modules and make stuff happen

Steal works slightly differently depending on how it is installed.  There
are three ways to install Steal:

 - [npm](#section_NPMbasics)
 - [bower](#section_Bowerbasics)
 - [download](#section_Downloadbasics)

## NPM basics

The following details how to use steal installed via [npm](https://www.npmjs.com/) to make
a simple jQuery app.

### Install

```
> npm install steal -S
> npm install jquery
```

Next to your application's _node_modules_ folder, create _myapp.js_ and
_myapp.html_:

```
/
  node_modules/
  package.json
  myapp.js
  myapp.html
```

### Add the script tag

In _myapp.html_, add a script tag that that loads _steal.js_ and points
to the [System.main main] entrypoint of your application. If
main is not provided, [System.main] will be set to _package.json_'s main.

```
<!-- myapp.html -->
<script src="./node_modules/steal/steal.js" main="myapp"></script>
```

### Configure

Steal reads your application's _package.json_ and all of its 
`dependencies`, `peerDependencies`, and `devDependencies` recursively.

Most configuration is done in the `system` property of 
package.json. The special npm configuration options are listed [npm here].


The following _package.json_ only loads the `dependencies`.

```
{
  "name": "myapp",
  "main": "myapp",
  "dependencies": {
    "jquery": "2.1.3"
  },
  "devDependencies": {...}
  "system": {
    "npmIgnore": ["devDependencies"]
  }
}
```

If there are problems loading some of your dependencies, read how to configure them on the [npm] page.

### Import modules and make stuff happen

In _myapp.js_, import your dependencies and write your app:

```
// myapp.js
import $ from "jquery";
$("body").append("<h1>Hello World</h1>")
```


## Bower basics

Using Bower is similar to using NPM but has a few options specific to how Bower works.

### Install

```
> bower install steal --save
> bower install canjs --save
```

### Use

If you are using a typical installation of Bower using it can be as simple as:

```html
<script src="bower_components/steal/steal.js" main="myapp"></script>
```

This will load your `bower.json` file and use your `dependencies` to configure packages
that you are using (such as CanJS in this example). Unlike NPM, with Bower your
`devDependencies` are not configured by default, although this may change in the future.
To enable the configuration of devDependencies add the following to your script tag:

```html
bower-dev="true"
```

#### Specifying components folder

Unlike NPM, Bower allows you to configure an alternate folder to install dependencies
rather than the default `bower_components`. If you are using a different folder
you can specify that as an attribute in the script tag as well:

```html
bower-path="vendor"
```

Will look for dependencies in `System.baseURL` + "/vendor".

### Importing in your app

From here using packages is the same as if you used NPM, just import them into
_myapp.js_ and do what you need:

```js
import can from "canjs";

var renderer = can.stache("<h1>StealJS {{what}}</h1>");
can.$("body").append(renderer({
	what: "rocks!"
}));
```

## Download basics

The following details how to use steal installed via its download to
make a basic jQuery app.

### Install

[Download Steal](https://github.com/bitovi/steal/archive/master.zip) and unzip into your application's folder. 

In your application's folder, create _myapp.js_,
_myapp.html_ and _config.js_. You should have something like:

```
/
  steal/
    ext/
    steal.js
    steal.production.js
  myapp.js
  myapp.html
```

### Add the script tag

In _myapp.html_, add a script tag that that loads _steal.js_ and points
to the [System.configPath configPath] and [System.main main] entrypoint of your application.


```
<!-- myapp.html -->
<script src="../path/to/steal/steal.js"
        config="./config.js"
        main="myapp">
</script>
```

### Configure

`config.js` is used to configure the behavior of
your site's modules. For example, to load jQuery from a CDN:

```
// config.js
System.config({
  paths: {"jquery": "http://code.jquery.com/jquery-1.11.0.min.js"}
});
```

> Note: Steal makes an AJAX request for the above example. Both client and server will need 
> to accept/handle CORS requests properly when using remote resources.


### Import modules and make stuff happen

In _myapp.js_, import your dependencies and write your app:

```js
// myapp.js
import $ from "jquery";
$("body").append("<h1>Hello World</h1>")
```

## Loader and System objects

Loader is a proposed constructor, allowing for the creating of custom ES6 module loaders. Documentation 
can be found [here](http://whatwg.github.io/loader/).

System is the proposed default Loader, allowing for APIs such 
as [System.import] and [System.config]. Documentation and polyfill information can be 
found [here](https://github.com/ModuleLoader/es6-module-loader).

Loader and System are currently 
polyfilled by [SystemJS](https://github.com/systemjs/systemjs).

## Configuring the `System` loader

Once steal.js loads, its startup behavior is determined
configuration values.  Configuration values can be set in three ways:

 - Set on a `steal` object prior to loading steal.js like:
  
        <script>
          steal = {main: "myapp"};
        </script>
        <script src="../path/to/steal/steal.js"></script>
   
 - Attributes on the steal.js script tag like:
  
        <script src="../path/to/steal/steal.js"
                main="myapp">
        </script>
 
 - Calling [System.config] or setting `System` configuration properties
   after `steal.js` has loaded. This technique is typically used in the [System.configMain] module.

        System.config({
          paths: {"can/*" : "http://canjs.com/release/2.0.1/can/*"}
        })
        System.meta["jquery"] = {format: "global"}
        
   If you are using bower or npm, your app's bower.json or package.json will be loaded automatically. System
   configuration happens in their `system` properties:
   
        {
          "name": "myapp",
          "dependencies": { ... },
          "system": {
            "map": {"can/util/util": "can/util/jquery/jquery"}
          }
        }

Typically, developers configure the [System.main] and [System.configPath] properties 
with attributes on the steal.js script tag like:

    <script src="../path/to/steal/steal.js"
            main="myapp"
            config-path="../config.js">
    </script>

Setting [System.configPath] sets [System.baseURL] to the 
configPath's parent directory.  This would load _config.js_ prior to
loading _../myapp.js_.

When _steal.js_ loads, it sets [System.stealPath stealPath].  [System.stealPath stealPath] sets default values
for [System.baseURL baseURL] and [System.configPath configPath]. If _steal.js_ is in _bower_components_,
[System.configPath] defaults to _bower_components_ parent folder. So if you write:

    <script src="../../bower_components/steal/steal.js"
            main="myapp">
    </script>

This will load `../../bower.json` before it loads `../../myapp.js`.

## Writing Modules

Once you've loaded and configured steal's behavior, it's time to start 
writing and loading modules.  Currently, [syntax.es6 ES6 syntax] is supported
in IE9+.  ES6 syntax looks like:

    import can from "can";
    
[@traceur Traceur Compiler] is used and all of 
of its [language features](https://github.com/google/traceur-compiler/wiki/LanguageFeatures) will work.

If you must support <IE8, use any of the other syntaxes.

Finally, steal supports [$less less] and [$css css] out of the box. Import, require, or
steal them into your project by adding a "!" after the filename.

    // ES6
    import "style.less!";
    
    // AMD
    define(["style.less!"],function(){ ... });
    
    // CommonJS
    require("style.less!");
    
    // steal
    steal("style.less!")

