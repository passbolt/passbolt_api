[jQuery UI](http://jqueryui.com/) for Browserify
================================

jQuery UI provides interactions like Drag and Drop and widgets like Autocomplete, Tabs and Slider and makes these as easy to use as jQuery itself.

If you want to use jQuery UI, go to [jqueryui.com](http://jqueryui.com) to get started. Or visit the [Using jQuery UI Forum](http://forum.jquery.com/using-jquery-ui) for discussions and questions.

This repo has a script to convert jQuery UI's modules into [browserify](http://browserify.org/) modules. It parses
the dependencies in the comments of the modules and generates equivalent `require` calls for them, along with `jquery`.

To install:

    npm install jquery jquery-ui

To use:

```javascript
// load jquery
var $ = require('jquery');

// load everything
require('jquery-ui');

// or load just the modules you need
require('jquery-ui/draggable');
require('jquery-ui/droppable');
require('jquery-ui/sortable');
```
