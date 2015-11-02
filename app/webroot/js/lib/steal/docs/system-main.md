@property {moduleName|Array<moduleName>} System.main
@parent StealJS.config

The name of the module(s) that loads all other modules in the application.

  @option {moduleName} The main module to load after [System.configMain]. 
  
  @option {Array<moduleName>} An array of main modules that will be loaded after [System.configMain].



@body

## Use

This is the starting point of the application. In
[System.env development], the `main` module is loaded after the [System.configMain] and [@dev] 
modules. In [System.env production], only the `main` module is loaded, but 
it is configured to load in a bundle.

Main should be configured by one of the approaches in [System.config].


## Use with npm

In [System.env development], your application's `package.json` will be read
and the main module set automatically.  For instance, if 
your package.json looks like:


```
{
  "main": "my/main.js",
  ...
}
```

The following will load `package.json` with the [npm] module and automatically load
`my/main.js`:

```
<script src="../node_modules/steal/steal.js">
</script> 
```

In [System.env production], make sure your script specifies `main` so the correct bundle to load
can be known.
