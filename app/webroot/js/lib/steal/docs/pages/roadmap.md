@page StealJS.roadmap Roadmap
@parent StealJS.guides

Here's what we've got planned:

## Watch Builds

Automatically build when a file changes. We should be able to make
this extremely high performance if the dependency graph is not changing.

## Live Reloading

Would make development extremely convenient as modules would automatically
reload as you change them without needing to reload the browser.

## Remove Traceur Runtime and IE8 ES6 module support

[Read here](https://groups.google.com/forum/#!topic/systemjs/yECCl6I9SDw) 

## Development Packages

Build a package that will be loaded in development. For example, instead of
loading each CanJS module individually, you could easily build them
into a package that would be loaded in development as a single file.
