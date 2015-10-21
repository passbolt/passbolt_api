@page can.mustache.Basics Basics
@parent can.mustache.pages 0

Mustache templates are logic-less templates with a simple syntax. The goal
with Mustache is to keep your templates as simple as possible.

Here's a quick example of what Mustache templates look like:

	{{#loggedIn}}
		Welcome back, {{name}}!
		You have {{messages.length}} new messages.
	{{/loggedIn}}
	{{^loggedIn}}
		Do you want to login?
	{{/loggedIn}}

Given the following data object:

	{
		name: "Austin",
		loggedIn: true,
		messages: new Array(10)
	}

The template would render the following:

	Welcome back, Austin!
	You have 10 new messages.

In general, it's best to try to ensure that the data passed to the template
already has any logic applied to it. However, CanJS's Mustache implementation
provides [can.mustache.Helpers helper tags] in addition to the basic tags which can be used for richer
functionality where modifying the data ahead of time isn't viable.

Just like [can.ejs EJS], Mustache can be used for [can.mustache.Binding live binding] templates. As opposed to
EJS, however, Mustache will automatically inject the `attr()` calls on
observable and compute objects to wire it up.

## Tags

Mustache templates are intended to be logic-less in that there are no `if`
statements or `for` loops. Tags handle all of the data injection for the template, 
taking in a key reference and then converting the result to a string.

Tags in Mustache are surrounded by double curly braces (a.k.a. mustaches) like
`{{key}}`. In this example, `key` references the `key` property on the context
scope passed to the template.

There are a number of basic tags that can be used to inject data into the template.

`[can.mustache.tags.escaped {{key}}]` will render the value referenced within the tag, 
escaping any HTML:

	Template:
		{{name}}

	Data:
		{ name: "Austin" }

	Result:
		Austin

`[can.mustache.tags.unescaped {{{key}}}]` or `[can.mustache.tags.unescaped2 {{&key}}]` will render the value referenced within the tag unescaped:

	Template:
		<div>{{name}}</div>

	Data:
		{ name: "<b>Austin</b>" }

	Result:
		<div><b>Austin</b></div>

`[can.mustache.helpers.section {{#key}}]` followed by `[can.mustache.helpers.close {{/key}}]` signify a [can.mustache.Sections section]. Sections will only render if the `key` references a value that is considered **truthy**. If the key 
is a reference to an object, it will also add a local context which can be referenced by 
tags within the section. Sections can also be used to iterate through an array, if that was 
the value referenced. In this example, the name will only be rendered if there is a 
person exists whom also has a name:

	Template:
		{{#person}}
			{{name}}
		{{/person}}

	Data:
		{
			person: {
				name: "Austin"
			}
		}

	Result:
		Austin

`[can.mustache.helpers.inverse {{^key}}]` followed by `[can.mustache.helpers.close {{/key}}]` signify an inverse [can.mustache.Sections section]. Inverted sections will only render if the `key` references a value that is considered **falsey**:

	Template:
		{{#person}}
			{{name}}
		{{/person}}
		{{^person}}
			No one.
		{{/person}}

	Data:
		{
			nobodyHere: true
		}

	Result:
		No one.

`[can.mustache.helpers.partial {{>key}}]` renders a partial template. Partials are used to nest other templates within another template:

	init.mustache:
		<div>
			<h1>{{title}}</h1>
			{{>person}}
		</div>

	person.mustache:
		Hi {{name}}!

	Data:
		{
			title: "Welcome",
			name: "Austin"
		}

	Result:
		<div>
			<h1>Welcome</h1>
			Hi Austin!
		</div>

`[can.mustache.tags.comment {{!key}}]` are comment tags. Comments won't get rendered (they're similar to HTML comments):

	Template:
		12345{{!These are numbers}}67890

	Data:
		{}

	Result:
		1234567890
