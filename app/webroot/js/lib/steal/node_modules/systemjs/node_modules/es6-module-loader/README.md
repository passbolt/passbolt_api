# ES6 Module Loader Polyfill [![Build Status][travis-image]][travis-url]

_For upgrading to ES6 Module Loader 0.16, [read the release notes here](https://github.com/ModuleLoader/es6-module-loader/releases/tag/v0.16.0)._

Dynamically loads ES6 modules in browsers and [NodeJS](#nodejs-use) with support for loading existing and custom module formats through loader hooks.

This project implements dynamic module loading through `System` exactly to the previous ES6-specified loader API at [2014-08-24 ES6 Specification Draft Rev 27, Section 15](http://wiki.ecmascript.org/doku.php?id=harmony:specification_drafts#august_24_2014_draft_rev_27) and is being converted to track the newly redrafted specification at https://github.com/whatwg/loader (work in progress at https://github.com/ModuleLoader/es6-module-loader/pull/317).

* Provides an asynchronous loader (`System.import`) to [dynamically load ES6 modules](#getting-started).
* Supports both [Traceur](https://github.com/google/traceur-compiler) and [Babel](http://babeljs.io/) for compiling ES6 modules and syntax into ES5 in the browser with source map support.
* Fully supports [ES6 circular references and live bindings](https://github.com/ModuleLoader/es6-module-loader/wiki/Circular-References-&-Bindings).
* Includes [`baseURL` and `paths` implementations](https://github.com/ModuleLoader/es6-module-loader/wiki/Configuring-the-Loader).
* Can be used as a [tracing tool](https://github.com/ModuleLoader/es6-module-loader/wiki/Tracing-API) for static analysis of modules.
* Polyfills ES6 Promises in the browser with an optionally bundled ES6 promise implementation.
* Supports IE8+, with IE9+ support for ES6 development without pre-compilation.
* The complete combined polyfill, including ES6 promises, comes to 9KB minified and gzipped, making it suitable for production use, provided that modules are [built into ES5 making them independent of Traceur](https://github.com/ModuleLoader/es6-module-loader/wiki/Production-Workflows).

For an overview of build workflows, [see the production guide](https://github.com/ModuleLoader/es6-module-loader/wiki/Production-Workflows).

For an example of a universal module loader based on this polyfill for loading AMD, CommonJS and globals, see [SystemJS](https://github.com/systemjs/systemjs).

### Documentation

* [A brief overview of ES6 module syntax](https://github.com/ModuleLoader/es6-module-loader/wiki/Brief-Overview-of-ES6-Module-syntax)
* [Configuring the loader](https://github.com/ModuleLoader/es6-module-loader/wiki/Configuring-the-Loader)
* [Production workflows](https://github.com/ModuleLoader/es6-module-loader/wiki/Production-Workflows)
* [Circular References &amp; Bindings](https://github.com/ModuleLoader/es6-module-loader/wiki/Circular-References-&-Bindings)
* [Extending the loader through loader hooks](https://github.com/ModuleLoader/es6-module-loader/wiki/Extending-the-ES6-Loader)
* [Tracing API](https://github.com/ModuleLoader/es6-module-loader/wiki/Tracing-API)

### Getting Started

If using ES6 syntax (optional), include `traceur.js` or `babel.js` in the page first then include `es6-module-loader.js`:

```html
  <script src="traceur.js"></script>
  <script src="es6-module-loader.js"></script>
```

To use Babel, load Babel's `browser.js` instead and set the transpiler to `babel` with the loader configuration:

```html
<script>
  System.transpiler = 'babel';
</script>
```

Then we can write any ES6 module:

mymodule.js:
```javascript
  export class q {
    constructor() {
      console.log('this is an es6 class!');
    }
  }
```

and load the module dynamically in the browser

```html
<script>
  System.import('mymodule').then(function(m) {
    new m.q();
  });
</script>
```

The dynamic loader returns a `Module` object, which contains getters for the named exports (in this case, `q`).

#### Setting transpilation options

If using Traceur, these can be set with:

```javascript
System.traceurOptions = {...};
```

Or with Babel:

```javascript
System.babelOptions = {...};
```

#### Module Tag

As well as defining `window.System`, this polyfill provides support for the `<script type="module">` tag:

```html
<script type="module">
  // loads the 'q' export from 'mymodule.js' in the same path as the page
  import { q } from 'mymodule';

  new q(); // -> 'this is an es6 class!'
</script>
```

Because it is only possible to load ES6 modules with this tag, it is not suitable for production use in this way.

See the [demo folder](https://github.com/ModuleLoader/es6-module-loader/blob/master/demo/index.html) in this repo for a working example demonstrating module loading in the browser both with `System.import` and with the module-type script tag.

#### NodeJS Use

```
  npm install es6-module-loader babel traceur
```

It is important that Babel or Traceur is installed into the path in order to be found, since these are no longer project dependencies.

For use in NodeJS, the `Loader` and `System` globals are provided as exports:

index.js:
```javascript
  var System = require('es6-module-loader').System;
  /*  
   *  Include:
   *    System.transpiler = 'babel'; 
   *  to use Babel instead of Traceur
   */

  System.import('some-module').then(function(m) {
    console.log(m.p);
  });
```

some-module.js:
```javascript
  export var p = 'NodeJS test';
```

Running the application:
```
> node index.js
NodeJS test
```

## Contributing
In lieu of a formal styleguide, take care to maintain the existing coding style. Add unit tests for any new or changed functionality. Lint and test your code using [grunt](https://github.com/cowboy/grunt).

_Also, please don't edit files in the "dist" subdirectory as they are generated via grunt. You'll find source code in the "lib" subdirectory!_

## Testing

- `npm run test:node` will use node to  to run the tests
- `npm run test:browser` will run `npm run test:browser-babel` and `npm run test:browser-traceur`
- `npm run test:browser-[transpiler]` use karma to run the tests with Traceur or Babel.
- `npm run test:browser:perf` will use karma to run benchmarks

`npm run test:browser-[transpiler]` supports options after a double dash (`--`) :

- You can use the `--polyfill` option to test the code with polyfill.

- You can use the `--coverage` option to test and extract coverage info.

- You can use the `--ie8` option to test the code in the ie8 scope only.

- You can use the `--saucelabs` option to use karma and saucelabs to run the tests in various browsers.
Note: you will need to export your username and key to launch it.

  ```sh
  export SAUCE_USERNAME={your user name} && export SAUCE_ACCESS_KEY={the access key that you see once logged in}
  npm run test:browsers -- --saucelabs
  ```

## Credit
Copyright (c) 2015 Luke Hoban, Addy Osmani, Guy Bedford

## License
Licensed under the MIT license.

[travis-url]: https://travis-ci.org/ModuleLoader/es6-module-loader
[travis-image]: https://travis-ci.org/ModuleLoader/es6-module-loader.svg?branch=master
