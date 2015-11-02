@page canjs CanJS
@parent javascriptmvc 0

@body

This is the detailed documentation of the API for CanJS 2.2, a framework for building
web applications that provides a lightweight inheritance system, observable
objects and values, and a powerful MVC core with live-bound templates, among other
resources. 

If you are just starting with CanJS, you may want to try our [getting started guide](../guides/Tutorial.html).

CanJS is composed of modules on the left. The following are typically distributed as part of the core
framework:

 - [can.Component] - widgets built on custom tags
 - [can.Construct] - inheritable constructor functions
 - [can.Control] - declarative event bindings
 - [can.Map], [can.List], [can.compute] - observable objects, list, and values.
 - [can.Model] -  observes connected to a RESTful JSON interface
 - [can.view] - template loading, caching, rendering
 - [can.mustache] - Live binding Handlebars and Mustache templates
 - [can.route] -  back button and bookmarking support
 
The following modules are typically distributed as plugins:

 - [can.Map::define] - control the behavior of attributes on a can.Map
 - [can.stache] - live binding templates
 - [can.Construct.proxy] - Proxy construct methods
 - [can.Construct.super] - Call super methods
 - [can.Map.delegate] - Listen to Observe attributes
 - [can.Map.setter] - Use setter methods on Map
 - [can.Map.attributes] - Define Observe attributes
 - [can.Map.validations] - Validate attributes
 - [can.Map.backup] - Backup and restore an Observe's state
 - [can.Control.plugin] - Registers a jQuery plugin function for Controls[1]
 - [can.view.modifiers View modifiers] - Use jQuery modifiers to render views[1]


You can use it out of the box on top of jQuery, Zepto, YUI, and Mootools,
and it's only about 20K.

@api canjs