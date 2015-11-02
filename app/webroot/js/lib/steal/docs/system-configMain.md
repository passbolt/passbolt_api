@property {moduleName} System.configMain
@parent StealJS.config

The name of a module that is loaded before [@dev] and [System.main].

@option {moduleName} A configuration module that is loaded before 
the [System.main] module(s). This is where all configuration 
should happen. The `configMain` module and all of its dependencies
run during a build, so make sure they can operate without a DOM.

@body

## Use


The `configMain` name and path is typically specified with [System.configPath] in the steal.js `<script>` tag like:

    <script src="../path/to/steal/steal.js"
            config-path="../path/to/stealconfig.js"
            main="app">
    </script>

This sets `configMain = "stealconfig.js"`.  


## Use with npm

If _steal.js_ is inside _node\_modules_ like:

    <script src="../node_modules/steal/steal.js">
    </script>

`configMain` will be set to `"package.json!npm"`.

