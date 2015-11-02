@page StealJS.project-exporting Project Exporting
@parent StealJS.guides 2

StealJS can export your project into commonly used formats and platforms
which can be used to create distributables that can be used in almost any situation:

 - [syntax.amd] and Bower
 - [syntax.CommonJS] and NPM for [Browserify](http://browserify.org/)
 - [syntax.es6 ES Syntax] and StealJS, SystemJS, or JSPM
 - [syntax.global global format] and `<script>` tags

This guide uses the [steal-tools.grunt.export] task to call [steal-tools.export] which loads the module source and transpiles it to _AMD_, _CommonJS_ and _global_ compatible distributables. This guide uses the [bit-tabs](https://github.com/bitovi-components/bit-tabs) component built with CanJS, but the same techniques can be used to create and export projects that use any other framework or library.

## Project Structure 

The Steal export process reads the contents of the project's source directory to generate the distributables. Our `bit-tabs` example component uses the _src/_ directory.

> Create `src/` and create the main entry-point in `src/bit-tabs.js`. The `dist/` and the subdirectories will be created by export process
```
bit-tabs/
  /dist
  	/amd      - AMD build
  	/cjs	  - CJS/Browserify builds
  	/global   - Global / <script> build
  /src        - Source files
  /test       - Test source files
```

## package.json

The project's `package.json` is used to configure how Browserify or Steal loads your
project. The following walks through the important parts:

### "system"

The `system` property specifies SystemJS and StealJS overwrites. Set the [System.main] property as follows to tell SystemJS to use `src/bit-tabs.js` as the starting point of the application. The "npmIgnore" property tells StealJS to ignore processing the package.json files of certain dependencies.

> In the system property, create a "main" property that points to the main entry-point. And,
> set "npmIgnore" to ignore dependencies that aren't needed by the browser.

```
  "system": {
    "main": "src/bit-tabs",
    "npmIgnore": ["devDependencies"]
  },
```


### "main"

CJS/Browserify and StealJS will read the `main` property when requiring your package. Set the `main` property to the CommonJS output of the export process.

```
  "main": "dist/cjs/lib/bit-tabs",
```

### "dependencies"

Use npm to install your project's dependencies.  If your project includes css or LESS files,
include `cssify`.  Browserify will use it to bundle css files.

```
  "dependencies": {
    "can": "2.2.0-alpha.10",
    "cssify": "^0.6.0"
  },
```

Add `steal`, `steal-tools`, `grunt`, and `grunt-cli` to your project's devDependencies:

> Add your project's dependencies to _package.json_.

```
  "devDependencies": {
    "grunt": "~0.4.1",
    "grunt-cli": "^0.1.13",
    "steal": "0.6.0-pre.0",
    "steal-tools": "0.6.0-pre.2"
  },
```

### "browser" and "browserify"

Because our project will export CSS, we need to tell Browserify to 
run "cssify" on css files with a `transform`.  To make this work
with new and old versions of Browserify you must specify both the 
"browser" and "browserify" properties.

> Specify Browserify transforms.

```
  "browser": {
    "transform": ["cssify"]
  },
  "browserify": {
    "transform": ["cssify"]
  },
```

### "scripts"

Prior to publishing to `npm`, we need to build the distributables. We will create
a grunt build job that builds our project. For now, we will create a npm script command that points to a `grunt build` task which we will create in the next step:

> Create "prepublish" script command which points to `grunt build`.

```
  "scripts": {
    "test": "grunt test --stack",
    "prepublish": "./node_modules/.bin/grunt build"
  },
```



## Gruntfile.js

Finally, we use [Grunt](http://gruntjs.com/) for task automation. If Grunt isn't your thing, you can use [steal-tools.export steal-tool's export] method.

> Create a _Gruntfile.js_ that looks like the following code block.

```
module.exports = function (grunt) {

	grunt.loadNpmTasks('steal-tools');

	grunt.initConfig({
		"steal-export": {
			dist: {
				system: {
					config: "package.json!npm"
				},
				outputs: {
					"+cjs": {},
					"+amd": {},
					"+global-js": {},
					"+global-css": {}
				}
			}
		}
	});
	grunt.registerTask('build',['steal-export']);
};
```

The [steal-tools.grunt.export] grunt task above loads modules and transpiles them to CommonJS, AMD, and a global distributables. The [steal-tools.grunt.export] task requires a [steal-tools.SystemConfig]. In this example, `system.config` points to the `package.json` file which will hold the export configuration.


## Publishing

To generate your project, run:

```
> npm run pre-publish
```

If you have `grunt-cli` installed you can alternatively call grunt directly.


```
> grunt build
```

This should create the `dist/amd`, `dist/cjs` and `dist/global` directories
with the files needed to use your project AMD, CommonJS and  `<script>` tags respectively.

For now, you should inspect these files and make sure they work. Eventually,
we may release helpers that make it easy to test your distributables.

### To NPM

Run:

```
> npm publish
```

### To Bower

The first time you publish, you must regisiter your project and create a bower.json.

Register your project's name:

```
> bower register bit-tabs git://github.com/bitovi-components/bit-tabs
```

Create a [bower.json](https://github.com/bower/bower.json-spec#name). The
easiest thing to do is copy your `package.json` and remove any node
specific values. 

```
{
  "name": "bit-tabs",
  "version": "0.0.1",
  "description": "",
  "main": "dist/cjs/lib/bit-tabs",
  "dependencies": {
      "can": "2.2.0-alpha.10",
      "cssify": "^0.6.0"
  },
  "system": {
      "main": "src/bit-tabs",
      "npmIgnore": ["testee","cssify"]
  },
}
```

Once bower is setup, publishing to bower just means pushing a 
[semver](http://semver.org/) tag to github that matches your project's version.

```
> git tag v0.0.1
> git push origin tag v0.0.1
```

## Importing the Export

Developers need to know how to use your project. The following demonstrates what you need to tell them
depending on how they are using your project.

### NPM and StealJS

Simply import, require, or use define to load your project.

```
import "bit-tabs";
require("bit-tabs");
define(["bit-tabs"], function(){});
```


### NPM and CJS

Simply require your project.

```
require("bit-tabs")
```

### AMD

They must configure your project as a package:

```
require.config({
	    packages: [{
		    	name: 'bit-tabs',
		    	location: 'path/to/bit-tabs/dist/amd',
		    	main: 'dist/amd/src/bit-tabs'
	    }]
});
```

And then they can use it as a dependency:

```
define(["bit-tabs"], function(){

});
```

### Global / Standalone

They should add script tags for the dependencies and your project and a link
tag for your project's css:

```
	<head lang="en">
		<link rel="stylesheet" type="text/css" href="dist/global/bit-tabs.css">

		<script src='./node_modules/jquery/dist/jquery.js'></script>
		<script src='./node_modules/can/dist/can.jquery.js'></script>
		<script src='./node_modules/can/dist/can.stache.js'></script>
		<script src='dist/global/bit-tabs.js'></script>
		<script>
			$(function(){
				var frag = can.view("app-template", {});
				$("#my-app").html(frag);
			})
		</script>
	</head>
	<body>

	<script type='text/stache' id="app-template">
	  <can-import from="bit-tabs"/>
	  <bit-tabs>
		<can-panel title="CanJS">
		  CanJS provides the MV*
		</can-panel>
		<can-panel title="StealJS">
		  StealJS provides the infrastructure.
		</can-panel>
	  </bit-tabs>
	</script>

	<div id="my-app"></div>
```