<!--
@page contributing How to Contribute
@parent guides 4

@body
-->

Thank you for contributing to CanJS!  If you need any help setting up a CanJS development environment and fixing CanJS bugs, please reach out to us on the [#canjs IRC channel](http://webchat.freenode.net/?channels=canjs) or email (contact@bitovi.com).  We will happily walk you through setting up a the environment, creating a test, and submitting a pull request. Here is a video showing how to contribute to CanJS:

<iframe width="662" height="372" src="https://www.youtube.com/embed/56DoykBew38" frameborder="0" allowfullscreen></iframe>

## Reporting Bugs

To report a bug, please visit [GitHub Issues](https://github.com/bitovi/canjs/issues).

When filing a bug, it is helpful to include:

- Small examples using tools like [JSFiddle](http://jsfiddle.com/). You can fork the following CanJS fiddles:
  - [jQuery](http://jsfiddle.net/donejs/qYdwR/)
  - [Zepto](http://jsfiddle.net/donejs/7Yaxk/)
  - [Dojo](http://jsfiddle.net/donejs/9x96n/)
  - [YUI](http://jsfiddle.net/donejs/w6m73/)
  - [Mootools](http://jsfiddle.net/donejs/mnNJX/)
- Breaking unit tests (optional)
- Proposed fix solutions (optional)

Search for previous tickets, if there is one add to that one rather than creating another. You can also post on the [Forums](https://forum.javascriptmvc.com/canjs) or talk to us in [#canjs IRC channel](http://webchat.freenode.net/?channels=canjs).

## Installing 

1. <a href="https://github.com/bitovi/canjs/fork" target="_blank">Fork Canjs on GitHub.</a>
2. Clone it with: `git clone git@github.com:<your username>/canjs`

## Structure

After installing CanJS, you’ll find a folder for each feature of CanJS: `construct`, `control`, `model`, etc.

Within each feature folder, for example `construct`, you’ll find a file for:

* the implementation of the feature - `construct.js`
* a demo of the feature - `construct.html`
* an overview documentation page - `construct.md`
* the feature’s tests - `construct_test.js`
* a page to run those tests - `test.html`

Any plugins for that feature will be folders within the feature’s folder. Ex: `proxy`, `super`.

The `can/test` folder contains:

* an `index.html` page which runs all tests for each library in an iFrame.
* a test page for each library e.g. `jquery.html` which loads dependencies using our package manager StealJS
* a `build` folder which contains the same set of test files but for testing the build artifacts (like `can.jquery.js` etc.) you get from the download)
* an `amd` folder which runs the same tests for the AMD modules using RequireJS

The `can/util` folder contains the compatibility layer for each library.

## Contributing

When contributing, please include tests with new features or bug fixes in a feature branch until you're ready to submit the code for consideration; then push to the fork, and submit a pull request. More detailed steps are as follows:

1. Navigate to your clone of the CanJS repository - `cd /path/to/canjs`
2. Create a new feature branch - `git checkout -b html5-fix`
3. Make some changes
4. Update tests to accomodate your changes
5. Run tests and make sure they pass in all browsers
6. Update documentation if necessary
7. Push your changes to your remote branch - `git push origin html5-fix`
8. Submit a pull request! Navigate to [Pull Requests](https://github.com/bitovi/canjs/pulls) and click the 'New Pull Request' button. Fill in some details about your potential patch including a meaningful title. When finished, press "Send pull request". The core team will be notified about your submission and let you know of any problems or targeted release date.

## Testing

CanJS has a pretty comprehensive test suite that is constantly being added to. Each module has its own tests, that can be run by opening the `test.html` in each folder. It's important that all tests pass before sending a pull request. [TravisCI](https://travis-ci.org/bitovi/canjs) will determine if your commits pass the tests, but while you're developing you can run the QUnit tests locally.

There are 3 ways of running your tests locally, all of which will require you to have [NodeJS](http://nodejs.org/), [npm](http://npmjs.org/), [Grunt](http://gruntjs.com/) and all of the CanJS dev dependencies installed.

### Getting Set Up

1. Install NodeJS & npm - [NodeJS](http://nodejs.org/) or use `brew install nodejs`
2. Install Grunt - `npm install grunt-cli -g`
3. Navigate to your local clone of CanJS - `cd /path/to/canjs`
4. Install dependencies - `npm install`

### Running All Tests From CLI

1. Once you completed the steps above simply run `grunt test`.

### Running All Tests In Browser

1. `grunt connect:server:keepalive`
2. Open [http://localhost:8000/test/](http://localhost:8000/test/) in your browser.

### Running Individual Test Files

1. `grunt connect:server:keepalive`
2. Open [http://localhost:8000/](http://localhost:8000/) in your browser and then navigate to your intended file. 

CanJS supports the following browsers:

- Chrome Current-1
- Safari Current-1
- Firefox Current-1
- IE 7+
- Opera Current-1

## Documentation

If your changes affect the public API, please make relevant changes to the documentation. Documentation is found either inline or in markdown files in the respective directory. In order to view your changes in documentation you will need to run the [CanJS.com site](https://github.com/bitovi/canjs.com) locally and regenerate the docs.

Follow the instructions on [CanJS.com gh-pages branch](https://github.com/bitovi/canjs.com/tree/gh-pages#setup) to generate documentation from your local copy of canjs.

## Making a build

To make a build (standalone and AMD version) you will also need to have [[NodeJS](http://nodejs.org/), [npm](http://npmjs.org/), [Grunt](http://gruntjs.com/) and all of the CanJS dev dependencies installed.

### Getting Set Up

1. Install NodeJS & npm - [NodeJS](http://nodejs.org/) or use `brew install nodejs`
2. Install Grunt - `npm install grunt-cli -g`
3. Navigate to your local clone of CanJS - `cd /path/to/canjs`
4. Install dependencies - `npm install`

After you have completed those steps simply run `grunt build` and it will put the built files in the `can/dist` directory, making them ready for download.

## Style Guide

### Linting
Grunt provides a `quality` task to verify some basic, practical soundness of the codebase. The options are preset.

### Spacing
Indentation with tabs, not spaces.

`if/else/for/while/try` always have braces, with the first brace on the same line.  For example:

    if(foo){

    }
  
Spaces after commas.  For example:

    myfn = function(foo, bar, moo){ ... }

### Assignments

Assignments should always have a semicolon after them.

Assignments in a declaration should always be on their own line. Declarations that don't have an assignment should be listed together at the start of the declaration. For example:

    // Bad
    var foo = true;
    var bar = false;
    var a;
    var b;

    // Good
    var a, b,
      foo = true,
      bar = false;

### Equality

Strict equality checks `===` should be used in favor of `==`. The only exception is when checking for undefined and null by way of null.

    // Bad
    if(bar == "can"){ ... }

    // Good
    if(bar === "can"){ ... }

If the statement is a truthey or falsey, use implied operators.  Falseys are when variables return `false`, `undefined`, `null`, or `0`.  Trutheys are when variables return `true`, `1`, or anything defined.

For example:

    // Bad
    if(bar === false){ ... }

    // Good 
    if(bar){ ... }

    // Good
    var foo = [];
    if(!foo.length){ ... }

###  Quotes

Use double quotes.

    var double = "I am wrapped in double quotes";

Strings that require inner quoting must use double outside and single inside.

    var html = "<div id='my-id'></div>";

### Comments

Single line comments go OVER the line they refer to:

    // We need an explicit "bar", because later in the code foo is checked.
    var foo = "bar";

For long comments, use:
    
    /* myFn
     * Four score and seven—pause—minutes ago...
     */
    
### Documentation

The documentation for the different modules should be clear and consistent. Explanations should be concise, and examples of code should be included where appropriate. In terms of format and style, here are a few suggestions to keep the documentation consistent within a module and across all parts of CanJS:

#### When referencing another part of CanJS, make sure to link the first reference in a section.

For instance, when documenting `can.Component.scope`, the first reference to `can.Component`, `can.route`, or any other part of CanJS should be enclosed in square brackets, so that links to those docs are generated. Linking each occurrence isn't necessary, but all the other references should at least be surrounded by "grave accents" or tickmarks.

	This is an example of linking to [can.Component] from another page. If you reference
	`can.Component` later in this section, you don't have to link to it. All subsequent
	references to `can.Component` have grave accents or tickmarks surrounding them.
	
	### New Section
	
	Another section referencing [can.Component] starts this trend again...

**Note**: The one exception to this is on the module page. When documenting `can.Component` itself, only use the tickmarks, as linking to `can.Component` would just link to the page you are currently modifying.

#### Enclose string literals in tickmarks as they should appear in code

If the developer should type `"@"`, use the tickmarks to make this clear. This avoids the ambiguity of whether the apostrophes or quote marks are part of the text that should be typed. This also applies to any references to variable/key names (e.g., `scope` versus "scope" or **scope**).

#### Include a clear description of your example code

For a developer that's new to CanJS, the description of the example is likely more important than the example itself. Make sure there is a clear description of what the code should accomplish. This includes using all the techniques above. A good description should answer the question, "could you explain what this example code is doing?"
  
## List of heroes

The following lists everyone who's contributed something to CanJS.  If we've forgotten you, please add yourself.

First, thanks to everyone who's contributed to [JavaScriptMVC](https://github.com/bitovi/javascriptmvc/contributors)
and [jQueryMX](https://github.com/jupiterjs/jquerymx/contributors), and the people at
[Bitovi](http://bitovi.com/people/).  You deserve heaps of recognition as CanJS is direcly based
off JavaScriptMVC.  This page is for contributors after CanJS's launch. Thank you

- [noah](https://github.com/iamnoah)
([1](https://github.com/bitovi/canjs/commit/8193e359cde3b77a44c683ca9f8a5268fc9df44b),
[2](https://github.com/bitovi/canjs/commit/b865588710e7e7dd8a9588ebf8e8c0f4d19fd800),
[3](https://github.com/bitovi/canjs/commit/83a48e7bcb05ed9f179159f540b181db4dcf6e9c),
[4](https://github.com/Spredfast/canjs/commit/dc7ddd2dc619619f3955c31be1435c6f927b7a35),
[5](https://github.com/bitovi/canjs/pull/214), [6](https://github.com/bitovi/canjs/pull/310))
- [thecountofzero](https://github.com/thecountofzero)
([1](https://github.com/bitovi/canjs/commit/e920434fa53975013688d065ce2e304f225fae75),
[2](https://github.com/bitovi/canjs/commit/8e98186e00b7d6b88869baeb97244877f143034e),
[3](https://github.com/bitovi/canjs/pull/315))
- [roissard](https://github.com/roissard) ([1](https://github.com/bitovi/canjs/commit/44bc72063e429bbc3f8a9a696a3ae4a7e57d12c8),
[2](https://github.com/bitovi/canjs/commit/c711fe05e1cdc99c72df8ac0f415c2ccb536d197))
- [Michael Kebbekus](https://github.com/makebbekus)
([1](https://github.com/bitovi/canjs/commit/d658d4910e8f3f391e9394449efe2f0c67581dbe))
- Daniel Salet ([1](https://github.com/bitovi/canjs/commit/92487178255360d40b75be49681dc65cbfbf3e18))
- [Daniel Franz](https://github.com/daniel-franz)
([1](https://github.com/bitovi/canjs/commit/4aae36eea9d671a12f9c459733c48b6fd1e99af4))
- [trickeyone](https://github.com/trickeyone)
([1](https://github.com/trickeyone/canjs/commit/2c11f56e0a0511749243055276e3b806984b15fa))
- [rjgotten](https://github.com/rjgotten)
([1](https://github.com/bitovi/canjs/commit/92c98e7c80d5fd7357eae69a60313d3d06efbdcb))
- Amy Chen ([1](https://github.com/bitovi/canjs/commit/3eee6ba9c69410ac549b909b1c8f860e6d591612))
- [Max Sadrieh](https://github.com/ms)
([1](https://github.com/bitovi/canjs/commit/06c5a4b3d50d14c5881ee55642fa10f37b71af0b))
- [dimaf](https://github.com/dimaf)
([1](https://github.com/bitovi/canjs/commit/fc8a4d57c99a280025eb7c613cef92de28c3c160),
[2](https://github.com/bitovi/canjs/pull/145))
- [yusufsafak](https://github.com/yusufsafak) ([1](https://github.com/bitovi/canjs/pull/30))
- [verto](https://github.com/verto) ([1](https://github.com/bitovi/canjs/pull/32))
- [WearyMonkey](https://github.com/WearyMonkey) ([1](https://github.com/bitovi/canjs/issues/27))
- [cohuman](https://github.com/cohuman)
([1](https://github.com/bitovi/canjs/pull/23), [2](https://github.com/bitovi/canjs/pull/26))
- [roelmonnens](https://twitter.com/roelmonnens)
- [Craig Wickesser](https://github.com/mindscratch) ([1](https://github.com/bitovi/canjs/pull/188))
- [Jeff Rose](https://github.com/jeffrose) ([1](https://github.com/bitovi/canjs/pull/201))
- [Brad Momberger](https://github.com/bmomberger-reciprocity) ([1](https://github.com/bitovi/canjs/pull/292))
- [Pablo Aguiar](https://github.com/scorphus) ([1](https://github.com/bitovi/canjs/pull/303),
[2](https://github.com/bitovi/canjs/pull/313),
[3](https://github.com/bitovi/canjs/pull/317))
- [David Schovanec](https://github.com/schovi) ([1](https://github.com/bitovi/canjs/pull/325),
[2](https://github.com/bitovi/canjs/pull/332))
- [Dan Connor](https://github.com/onyxrev) ([1](https://github.com/bitovi/canjs/pull/284))
- [Jesse Baird](https://github.com/jebaird) ([1](https://github.com/bitovi/canjs/pull/319))
- [Eric Kryski](https://github.com/ekryski) ([1](https://github.com/bitovi/canjs/pull/917))

for helping us with new features, bug fixes, and getting this out the door.
