@property {Object.<String,moduleName>} System.ext
@parent StealJS.config

Configures plugin loading by module extension.

@option {Object.<String,moduleName>}

Specifies a plugin to add when an extension is matched in a module name. `steal.js` includes
defaults of:

    System.ext //-> {"css": "$css", "less": "$less"}

@body

## Use

The following:

```
System.config({
	ext: {
		stache: 'can/view/stache/system'
	}
})
```

allows:

    System.import("foo.stache!")

Without having to write:

    System.import("foo.stache!can/view/system/stache");

By default, `steal.js` configures `css` to point to "$css" and `less` to point to "$less".