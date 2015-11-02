@function syntax.es6 ES6 
@parent StealJS.syntaxes

@body

## Use

The ES6 syntax in the module syntax that will be part of the EMCAScript 6 standard currently in development. 
Because ES6 syntax is not valid in ES5 it requires compilation to use. 
Steal brings along [@traceur Traceur] to act as the ES6 compiler, which it does on the 
fly. When developing you won't even notice that it is loaded, and when 
built your modules are compiled down to ES5.

ES6 uses the keywords `import` and `export` for importing and exporting respectively. For example:

    import can from "can";

This will import `can` from the can module, assuming it is the default exported value. If a module exports several values you can specify which to import using curly braces. Take an example of a `Math` module that exports several functions to do operations; it might be defined like so:

```
export function add(a, b) {
  return a + b;
}

export function subtract(a, b) {
  return a - b;
}
```

To import one or more of these exported values use curly braces in your module like so:

    import { add, subtract } from "math";

You can export any type of value, like a normal var:

    export var foo = "bar";

If you want to export a single value you can do so using the `default` keyword like so:
```
export default function add(a, b){
  return a + b;
}
```
Because Traceur is a full ES6 to ES5 compiler you can use many ES6 features beyond just module loading. Listing these is beyond the scope of this document, but you can check out many of the language features Traceur supports [here](https://github.com/google/traceur-compiler/wiki/LanguageFeatures).

## Browser Support

The ES6 syntax is only available in IE9+. The [@traceur Tracer Runtime] requires `Object.defineProperty`. PhantomJS 1.x is also not supported due to limited support for getters and setters.
