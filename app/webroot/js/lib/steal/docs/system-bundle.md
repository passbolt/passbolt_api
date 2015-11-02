@property {(Array.<moduleName>|Glob)} System.bundle
@parent StealJS.config

Specifies which modules will be progressively loaded.  This is 
used by the build.

@option {Array.<moduleName>}

Array of module names.

@option {Glob}

A glob pattern used to match module names. For example:

    System.bundle = "components/*/*";

Would match `components/home/home` and `components/admin/admin` but not `utils/collections/find`.

@body

## Use

It is possible to load an app in chunks, rather than one single production file. If there is modules segmented by "pages", for example:

- A home screen in "js/pages/home"
- Search results in "js/pages/search"
- Details in "js/pages/details"

It will be more efficient to load "search" and "details" progressively, making the "home" page load lighter. `System.bundle` allows you to create multiple production files by defining the starting point:

    System.bundle = ["js/pages/home","js/pages/search","js/pages/details"]

Within the main application, the condition may exist such as:

```
import $ from 'jquery';

if(/*route === home*/) {
	System.import('js/pages/home', function() {});
}
```
