@module {*} bower
@parent StealJS.modules

@signature `moduleName!bower`

@param {moduleName} moduleName The moduleName of the bower configuration file to
process, usually `bower.json`.

@body

## Use

The `bower` plugin makes it easy to work in projects that use a lot of Bower dependencies.
Instead of configuring each package individually you can use your `bower.json`
file in place of the [System.configMain] and all of your dependencies will be
pre-configured for you.

By default, if [System.stealPath] points to steal.js within bower_components like:

    <script src="bower_components/steal/steal.js"></script>

[System.configMain] will point to `"bower.json!bower"`. The `bower` plugin reads your
bower.json file and configure each dependency so they can be automatically loaded.

## Options

### System.bowerPath

Specifies the path to the folder where Bower dependencies can be found.

When using the [Bower plugin](https://github.com/bitovi/system-bower) by default it will assume dependencies are located at `System.baseURL` + `/bower_components`, which is the default location that Bower installs dependencies. Since this is configurable by Bower itself, `bowerPath` provices a way to point to the directory where you install Bower dependencies.  Using in the script tag is the best option:

```html
<script src="vendor/steal/steal.js"
	data-bower-path="vendor"
	data-main="main"></script>
```

Would load the Bower configuration file for, for example, `lodash` in `vendor/lodash/bower.json`.

### System.bowerDev

Specifies whether `devDependencies` included in your bower.json will be included in configuration.

By default, the [Bower plugin](https://github.com/bitovi/system-bower) will only load configurations for deps listed in `dependencies`. In some cases you might want to also load the `devDependencies`, for example when running unit tests. To do so just include `bowerDev` in the script tag when loading Steal:

```html
<script src="bower_components/steal/steal.js"
	data-bower-dev="true"
	data-main="tests"></script>
```

## Configuration

`bower.json` configures the behavior of a package and all dependent packages. This section
contains a list of properties that are used by the Bower plugin to configure the loader.

### package.main

Specifies the [System.main] property unless already defined (such as setting the main
in your `<script>` tag).

### package.system

By default, any property on the package.system object is passed to [System.config]. A few properties have special behavior, however:

### package.system.main

The moduleName of the initial module that shoudl be loaded when the package is imported. This overrides the `package.main` value. Useful when you need to have a `main` that is available for all bower users (like a global script) and a main available for users of the bower plugin (perhaps the CommonJS source):

```json
{
  "name": "my-module",
  "version": "1.0.0",
  "main": "dist/global.js",
  "system": {
    "main": "my-main"
  }
}
```

This will load `my-main` as the main module instead of `dist/global.js`.

### package.system.bowerIgnore

Use bowerIgnore to specify dependencies to prevent package information from being loaded. The following example ignores the bower package `jquery-cookie`:

```js
{
  "dependencies": {
    "can": "2.2.4",
    "jquery-cookie": "1.0.0"
  },
  "system": {
    "bowerIgnore": [ "jquery-cookie" ]
  }
}
```

This will load `can` but ignore `jquery-cookie`.

### package.system.configDependencies

Defines dependencies of your bower package. This is useful for loading modules,
like extensions, that need to be initialized before the rest of your application
is imported. For example you can use both bower and [npm] dependencies by setting
your `package.json` as a configDependency:

```js

{
  "name": "my-project",
  "system": {
    "configDependencies": [
      "package.json!npm"
    ]
  }
}
```
