@function can.stache.helpers.partial {{>key}}
@parent can.stache.tags 6

@signature `{{>key}}`

Render another template within the current template.

@param {can.stache.key} key A key that references a value within the current or parent 
[can.stache.context context]. If the value is a function or [can.compute], the 
function's return value is used.

If the key value is:

 - `undefined` - the key's name (ex: user.stache in `{{>user.stache}}`) is used to
   look up a template using [can.view].

 - `string` - the string value is used to lookup a view in [can.view].

 - `function` - the function is called with the current scope.

[can.view] looks for a template in the following places:

1. A registered view
2. An id of an element
3. A url to load the template.

@return {String} The value of the rendered template is inserted into
the page.


@body

Partials are templates embedded in other templates.  Partials begin with a greater than sign, like `{{>my_partial}}`.  Partials inherit the calling context.  

Partials render at runtime, so recursive partials are possible but make sure you avoid infinite loops.

For example, this template and partial:

__base.mustache__

```
<h2>Names</h2>
{{#names}}
	{{>user.stache}}
{{/names}}
```

__user.mustache__

```
<strong>{{name}}</strong>
```

The resulting expanded template at render time would look like:

```
<h2>Names</h2>
{{#names}}
	<strong>{{name}}</strong>
{{/names}}
```

## Acquiring Partials

__Referencing Files__

Partials can reference a file path and file name in the template.

The following template uses a relative path (relative to the current page):

```
<script id="template" type="text/stache">
	{{>views/test_template.stache}}
</script>
```

The following template uses an absolute path (rooted to steal's root directory):

```
<script id="template" type="text/stache">
	{{>//myapp/accordion/views/test_template.stache}}
</script>
```

__Referencing by ID__

Partials can reference templates that exist in script tags on the page by 
referencing the `id` of the partial in the template.  For example:

```
<script id="mytemplate" type="text/stache">
	{{>mypartial}}
</script>
```

```
<script id="mypartial" type="text/stache">
   	I am a partial.
</script>
```

```
var template = can.view("#mytemplate", {});
```

__Manually Registering__

Partials can be manually registered by calling `can.view.registerView` 
and passing an identifier and content.  For example:

```
can.view.registerView('myTemplate', "My body lies over {{.}}")
```

in the template, you reference the template by the identifer you registered:

```
{{>myTemplate}}
```

resulting in the template rendering with the current context applied to the partial.

## Passing Partials in Options

Partials can resolve the context object that contains partial identifiers.
For example:

```
var template = can.view("#template", { 
	items: []
	itemsTemplate: "test_template.stache" 
});

can.$(document.body).append(template);
```

then reference the partial in the template just like:

```
<ul>
{{#items}}
	<li>{{>itemsTemplate}}</li>
{{/items}}
</ul>
```
