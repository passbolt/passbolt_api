@page can.util can.util
@parent canjs

can.util adds the following utility functions to CanJS:

- [can.$ $] - make a dependency library's NodeList
- [can.Deferred Deferred] - create a new Deferred object
- [can.Object.same Object.same] - checks if two Objects are the same
- [can.Object.subset Object.subset] - determines if an Object is a subset of another Object
- [can.Object.subsets Object.subsets] - returns the Object that contain the subset
- [can.addClass addClass] - add a class to Elements in a NodeList
- [can.ajax ajax] - make an AJAX request
- [can.append append] - append content to the Elements of a NodeList
- [can.batch batch] - specify atomic operations by preventing/allowing change events
- [can.bind bind] - listen for events from an object
- [can.buildFragment buildFragment] - make a document fragment
- [can.camelize camelize] - capitalize the first letter after each hyphen in a string
- [can.capitalize capitalize] - capitalize the first letter of a string
- [can.data data] - associate data with or retrieve data from DOM nodes
- [can.delegate delegate] - listen for events from the children of an Element
- [can.deparam deparam] - convert a query String to an Object literal
- [can.each each] - iterate through an Array or Object
- [can.esc esc] - escapes a String for insertion into HTML
- [can.events events] - adds the attributes, inserted, and removed DOM events to the base library
- [can.extend extend] - merge a number of Objects together
- [can.frag frag] - convert various objects into a DocumentFragment
- [can.getObject getObject] - retrieve an object from a String path
- [can.hyphenate hyphenate] - adds a hyphen before each uppercase letter and converts the entire string to lower case
- [can.isArray isArray] - check if an object is an array
- [can.isDeferred isDeferred] - check if an object is a Deferred
- [can.isEmptyObject isEmptyObject] - check if an object has no properties
- [can.isFunction isFunction] - check if an object is a function
- [can.makeArray makeArray] - convert an array-like object to an Array
- [can.off off] - stop listening for events on an object
- [can.on on] - listen for events on an object
- [can.param param] - serialize an object into a query String
- [can.proxy proxy] - bind a function to a context
- [can.remove remove] - remove Elements from the DOM
- [can.sub sub] - returns a string with {param} replaced by property values
- [can.trigger trigger] - trigger an event on an Object
- [can.trim trim] - trim whitespace from a String
- [can.unbind unbind] - stop listening for events on an Object
- [can.undelegate undelegate] - stop listening for events from the children of an Element
- [can.underscore underscore] - convert a CamelCase or mixedCase string and underscores the String on the capital letter
- [can.when when] - call a callback Function when a Deferred resolves