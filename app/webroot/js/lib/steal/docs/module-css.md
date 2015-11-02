@typedef {{}} $css
@parent StealJS.modules

@option {{}}
The `$css` module is configured to process CSS modules.  By default, 
extensions that end with `.css!` will use the `$css` module.  The default path to `$css`
module is `steal/css.js`.

@body

## Use

Specify a CSS dependency like:

    require("my.css!");

