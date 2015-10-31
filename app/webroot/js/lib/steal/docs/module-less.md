@typedef {{}} $less
@parent StealJS.modules

@option {{}}
The `@less` module is configured to process [LESS](http://lesscss.org) modules.  By default, 
extensions that end with `.less!` will use the `$less` module.  The default path to `$css`
module is `steal/less.js`.

@body

## Use

Specify a LESS dependency like:

    require("my.less!");