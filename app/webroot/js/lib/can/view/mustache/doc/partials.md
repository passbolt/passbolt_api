@page Partials
@parent can.Mustache 2

# Partials

Partials are templates embedded in other templates which execute at runtime.  
Partials begin with a greater than sign, like `{{>my_partial}}`.  

Partials are rendered at runtime, so recursive partials are possible but make sure you avoid infinite loops. They also inherit the calling context.

For example, this template and partial:

__base.mustache__

	<h2>Names</h2>
	{{#names}}
		{{>user.mustache}}
	{{/names}}

__user.mustache__

	<strong>{{name}}</strong>

The resulting expanded template at render time would look like:

	<h2>Names</h2>
	{{#names}}
		<strong>{{name}}</strong>
	{{/names}}

## Acquiring Partials

You can manually register partial templates by calling
`can.view.registerView` and passing an identifier and content.  For example:

	can.view.registerView('myTemplate', "My body lies over {{.}}")

then later in the view:

	{{>myTemplate}}

resulting in the template rendering with the current context applied to the partial.

Additionally, you can register partials that exist in script tags on the page.  For example:

	<script id="mytemplate" type="text/mustache">
		{{>mypartial}}
	</script>

	<script id="mypartial" type="text/mustache">
    	I am a partial.
	</script>

	var template = can.view("#mytemplate", {});

You can also reference a file as a partial.  For example:

	<script id="template" type="text/mustache">
    	{{>views/test_template.mustache}}
	</script>

	var template = can.view("#mytemplate", {});