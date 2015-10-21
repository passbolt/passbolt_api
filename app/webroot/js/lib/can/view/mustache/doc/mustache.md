@function can.mustache can.mustache
@parent canjs
@release 2.1
@group can.mustache.methods 0 Methods
@group can.mustache.pages 1 Pages
@group can.mustache.types 2 Types
@group can.mustache.tags 3 Basic Tags
@group can.mustache.htags 4 Helper Tags
@link ../docco/view/mustache/mustache.html docco
@test can/view/mustache/test/test.html
@plugin can/view/mustache
@download http://canjs.us/release/latest/can.mustache.js

@description Logic-less [http://mustache.github.io/ mustache] templates with live binding 
when used with [can.Maps](#can_observe).

@signature `can.mustache( [id,] template )`

Creates an instance of a mustache template. 

@param {String} [id] If two arguments are passed, the first argument is the id of the 
template that will be registered with [can.view].

@param {String} template The content of the mustache template.

@return {can.view.renderer} A function that renders the mustache template into
a live documentFragment.

@body

## Use

[Mustache](https://github.com/janl/mustache.js/) and [Handlebar](http://handlebarsjs.com/) 
templates are compatible with can.mustache.

Mustache templates looks similar to normal HTML except
they contain keys for inserting data into the template
and [can.mustache.Sections sections] to enumerate and/or filter the enclosed template blocks.

For example, the following renders a welcome header for
a user and displays the number of messages.

__Mustache Template__

	<script id="template" type="text/mustache">
		<h1>Welcome {{user}}!</h1>
		<p>You have {{messages}} messages.</p>
	</script>

__JavaScript__

	var data = new can.Map({
		user: 'Tina Fey',
		messages: 0
	});

	var template = can.view("#template", data)
	document.body.appendChild(template);

__HTML Result__

	<h1>Welcome Tina Fey!</h1>
	<p>You have 0 messages.</p>

To update the html using live-binding, change an observable value:

	data.attr('message', 5)

This updates this paragraph in the HTML Result to:

	<p>You have 5 messages.</p>



can.mustache provides significantly more functionality such as:

- [can.mustache.Basics Context and Path Basics]
- [can.mustache.Sections Sections]
- [can.mustache.helpers.partial Partials]
- [can.mustache.Acquisition Acquiring Templates]
- [can.mustache.Helpers Helpers]
- [can.mustache.Binding Live Binding]

## Tags

@api can.mustache.tags