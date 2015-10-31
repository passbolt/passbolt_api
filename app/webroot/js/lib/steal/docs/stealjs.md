@page StealJS
@group StealJS.api apis
@group StealJS.guides guides

_Good artists copy; great artists steal._

StealJS is a module loader and builder that will help you create the next great app. Its designed to simplify dependency management while being extremely powerful and flexible.

StealJS is composed of two parts.
- [steal] - the module loader
- [steal-tools] - the builder

### The Module loader
[steal Steal] supports the future - [ES6 Module Loader](https://github.com/ModuleLoader/es6-module-loader) syntax -
with everything [traceur supports](https://github.com/google/traceur-compiler/wiki/LanguageFeatures),
while supporting [syntax.amd], and [syntax.CommonJS]. It can load [npm] modules without configuration.

Steal makes your development workflow simple and easy. Steal automatically
loads a [@config config] and [@dev development tools] module, supports css and less, and makes it easy to switch
between development and production [System.env environments].

### The Builder
[steal-tools Steal-tools] builds your application or exports your project to AMD, CommonJS or standalone formats.
And [steal-tools] can build progressively loaded apps that balance caching and script requests resulting in lightning-fast load times.

For more information, checkout [StealJS.why], or our step-by-step [StealJS.quick-start] guide to help you get up and running.
