@typedef {{logLevel: Number, warn: function(), log: function()}} @dev
@parent StealJS.modules

A module loaded in [System.env development] after [@config] but 
before [System.main].  It typically loads development-only 
tools.  By default, it loads `steal/dev.js` which provides the following
values on a global `steal.dev`:


@option {function()} log Writes out a message with `console.log` if `logLevel` is
less than 1.
@option {function()} warn Writes out a warning message if `logLevel` is less
than 2.
@option {Number} [logLevel=0] Controls what types of messages will be logged. By
default the logLevel is 0 so all messages will be logged.
@option {function()} assert Throws an error if the expression passed to it is falsy.

@body

## Use

Call `steal.dev.log` to log development info.  For example:

    steal.dev.log("app is initializing");

Call `steal.dev.warn` to log warning information.  For example:

    steal.dev.warn("something went wrong");

Call `steal.dev.assert` to test for truthiness of the expression provided. For example:

    steal.dev.assert("foo" === "bar"); // throws!

By default, [steal-tools] will remove `steal.dev.log`, `steal.dev.warn` and
`steal.dev.assert` calls from the built output.


