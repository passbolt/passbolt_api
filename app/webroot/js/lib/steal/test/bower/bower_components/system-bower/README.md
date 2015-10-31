# system-bower

This is a plugin for [SystemJS](https://github.com/systemjs/systemjs) and 
[StealJS](http://stealjs.com/) that makes it easy to work with [Bower](http://bower.io/).

The idea is to reduce the amount of manual configuring needed when using SystemJS/StealJS
and instead leverage the metadata included in `bower.json` to have the configuration
done for you.

## Install

If you're using StealJS you don't have have to install this plugin, it's included by default.

If you're using SystemJS install this as another bower dependency:

```js
bower install system-bower --save-dev
```

## Use

To set things up you'll want to import your `bower.json` file using the plugin which will
correctly configure the loader.

### StealJS

In your config file simply import your bower.json using this module as the plugin. It is named `bower`:

```js
// config.js

require("bower.json!bower");
```

In the future this won't be needed at all, and there will be a simpler way to enable the plugin.

### SystemJS

```js
System.paths.bower = "bower_components/system-bower/bower.js";
System.import("bower.json!bower").then(function() {
  // Configurations set, you can start importing stuff
});
```

## Configuring

This plugin allows for a couple of options to optimize customization:

### System.bowerPath

By default the plugin will assume dependencies are located at `bower_components`. Since a
lot of people like to place their dependencies in another folder name you can change the
lookup path with `System.bowerPath`.

```js
System.bowerPath = "vendor";
```

With StealJS you can add this to your script tag:

```html
<script src="bower_components/steal/steal.js"
  data-bower-path="vendor"></script>
```

### System.bowerDev

Using `System.bowerDev` will enable loading of `devDependencies`. This is useful when testing.
It will only load your root devDependencies, not those of your dependencies. To enable
just make the value truthy:

```js
System.bowerDev = true;
```

or with StealJS

```html
<script src="bower_components/steal/steal.js"
  data-bower-dev="true"></script>
```

## License

MIT
