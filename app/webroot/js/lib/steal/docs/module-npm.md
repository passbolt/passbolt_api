@module {*} npm
@parent StealJS.modules

@signature `moduleName!npm`

@param {moduleName} moduleName The moduleName of the file you want 
to process. This will normally be a package.json of your base application.

@body

## Use

The `npm` plugin makes it easy to work with npm packages. By pointing it 
at a `package.json`, you will be able to import npm packages as modules.

By default, if [System.stealPath] points to steal.js within node_modules like:

    <script src="../node_modules/steal/steal.js"></script>
    
[System.configMain] will point to `"package.json!npm"`. The `npm` plugin
reads `package.json` and sets a normalize and locate hook.  


## NPM Module names

Package dependency module names are converted to look like: 

> packageName@version#modulePath!pluginName

For example, code that import's jQuery like:

    import $ from "jquery"

might actually import:

    jquery@2.1.3#./dist/jquery.js



## Configuration

`package.json` configures the behavior of a package and even dependency 
packages.  This section lists the configurable properties and behaviors that
steal uses.  

### package.main

Specifies the [System.main] property unless it is overwritten by `package.browser` or
`package.system.main`. 

```
{
  "main": "myapp"
}
```

### package.browser

Specifies browser-specific overwrites for module file resolution.  This 
behaves like Browserify's [browser field](https://github.com/substack/node-browserify#browser-field).

```
{
  "browser": {
    "fs": "level-fs",
    "./lib/ops.js": "./browser/opts.js"
  }
}
```

### package.globalBrowser

Global browser specific overwrites for module file resolution.  These mapping take effect
for all projects.  Use sparingly. Add [system-npm-browser-shims](https://github.com/bitovi/system-npm-browser-shims)
as a dependency for a nearly comprehensive list.

```
{
  "globalBrowser": {
    "console": "console-browserify",
    "constants": "constants-browserify"
  }
}
```

### package.system

By default, any property on the package.system object is passed to [System.config]. However, the 
following properties have special behavior:

### package.system.main

The moduleName of the initial module that should be loaded when the package is imported. For example:

```
{
  "name": "my-module",
  "version": "1.2.3",
  "system": {
    "main": "my-main"
  }
}
```

When `my-module` is imported, `my-module@1.2.3#my-main` will be the actual module name being 
imported.  This path that `my-main` will be found depends on the `directories.lib` setting.


### package.system.map

The map config works similar to the base [System.map] behavior.  However, both the keys and values
are converted to NPM module names.  The keys and values must:

 - Start with `./` to map modules within the package like `"./src/util"`, or
 - Look like `packageName#./modulePath` to map direct dependencies of the package.
 
```js
{
  "system": {
    "map": {
      "./util/util": "./util/jquery/jquery",
      "jquery" : "lodash"
    }
  }
}
```

### package.system.meta

The meta config works similar to the base [System.meta] behavior.  However, the module names must:

 - Start with `./` to add metadata to modules within the package like `"./src/util"`, or
 - Look like `packageName#./modulePath` to add metadata to direct dependencies of the package.

Example:

```js
{
  "system": {
    "meta": {
      "./src/utils": {"format": "amd"},
      "jquery": {"format": "global"},
      "lodash#./array/grep": {"format": "es6"}
    }
  }
}
```

### package.system.npmIgnore

Use npmIgnore to prevent package information from being loaded for specified dependencies
in the `peerDependencies`, `devDependencies` or `dependencies`.  The following
ignores a package.json's `devDependencies` and `cssify`.  But all other
dependencies will be loaded:

```js
{
  "dependencies": {
    "can": "2.2.4",
    "cssify": "^0.6.0"
  },
  "devDependencies": {
    "steal-tools": "0.5.0"
  },
  "system": {
    "npmIgnore": ["devDependencies","cssify"]
  }
}
```

The following packages are ignored by default:

 - "steal", "steal-tools"
 - "bower"
 - "grunt", "grunt-cli"

### system.npm.npmDependencies

Like `npmIgnore` but affirmative. If used alone will only include the dependencies listed. If used in conjunction with `npmIgnore` acts as an override. For example the following config:

```js
{
  "dependencies": {
    "one": "1.0.0",
	"two": "1.0.0"
  },
  "system": {
    "npmDependencies": [ "one" ]
  }
}
```

Will load `one` but ignore `two`.

When used in conjuction with `npmIgnore`:

```js
{
  "devDependencies": {
	"one": "1.0.0",
	"two": "1.0.0",
	"three": "1.0.0"
  },
  "system": {
	"npmIgnore": [ "devDependencies" ],
	"npmDependencies": [ "one" ]
  }
}
```

Even though `npmIgnore` is set to ignore all `devDependencies` the use of `npmDependencies` acts as an override. The package `one` will be loaded, but not `two` or `three`.

### system.npm.ignoreBrowser

Set to true to ignore browserfy's `browser` and `browserify` configurations.

```js
{
  "system": {
    "ignoreBrowser": true
  }
}
```

### system.npm.directories

Set a folder to look for module's within your project.  Only the `lib` 
directory can be specified.

In the following setup, `my-project/my-utils` will be looked for in
`my-project/src/my-utils.js`:

```js
{
  "name": "my-project"
  "system": {
    "directories" : {
      "lib" : "src"
    }
  }
}
```

### system.npm.configDependencies

Defines dependencies of your npm package. This is useful for loading modules,
like extensions, that need to be initialized before the rest of your application
is imported. For example you can use both npm and [bower] dependencies by setting
your `bower.json` as a configDependency:

```js

{
  "name": "my-project",
  "system": {
    "configDependencies": [
      "bower.json!bower"
    ]
  }
}
```
