@typedef {*} @loader
@parent StealJS.modules

`@loader` is primarily only needed to be used in your [@config] to ensure that you are configuring
the correct loader. Since [System](steal#section_LoaderandSystemnamespaces) represents the global System loader using it within your
config doesn't guarantee that you are configuring the correct loader. In scenarios where
you want to build multiple apps in parallel, for example if you have several apps set
up to build in a Grunt task, using `loader` is necessarily so that your [@config] options
are set on the proper loader.

@option {*} The `@loader` module is the `Loader` that is loading your code.

@body

## Use

To use `@loader` simply import it into your config and use it in the same way you
would use [System](steal#section_LoaderandSystemnamespaces).

    import loader from "@loader";

    loader.config({
      map: {
        "can/util/util": "can/util/jquery/jquery"
      }
    });

This works with any syntax supported by StealJS.
