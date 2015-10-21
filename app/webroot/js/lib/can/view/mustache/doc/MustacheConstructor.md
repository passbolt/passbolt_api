@constructor can.MustacheConstructor can.Mustache
@release 1.1

@deprecated {2.1} As of 2.1, this is considered an internal API.  Use [can.mustache] to create
templates.

@description Logic-less [http://mustache.github.io/ mustache] templates with live binding 
when used with [can.Maps](#can_observe).

@signature `new can.Mustache(options)`

Creates an instance of a mustache template. This is typically not used directly in 
favor of [can.view] or [can.mustache].

@param {{}} options An options object with the following properties:

@option {String} text The text of the mustache template.
@option {String} [name] The name of the template used to identify it to
debugging tools.

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