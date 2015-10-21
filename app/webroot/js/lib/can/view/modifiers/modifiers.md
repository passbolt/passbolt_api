@page can.view.modifiers jQuery modifiers
@parent can.view.plugins
@plugin can/view/modifiers
@test can/view/modifiers/test.html

The can/view/modifiers plugin extends the jQuery view modifiers

* [jQuery.fn.after .after()]
* [jQuery.fn.append .append()]
* [jQuery.fn.before .before()]
* [jQuery.fn.html .html()]
* [jQuery.fn.prepend .prepend()]
* [jQuery.fn.replaceWith .replaceWith()]
* [jQuery.fn.text .text()]

to render a [can.view]. When rendering a view you call the view modifier the same way
as can.view with the view name or id as the first, the data as the second and the optional
success callback (to load the view asynchronously) as the third parameter.
For example, you can render a template from *todo/todos.ejs* looking like this:

	<% for(var i = 0; i < this.length; i++ ){ %>
	  <li><%= this[i].name %></li>
	<% } %>

By calling the [can.prototype.jQuery.fn.html html] modifier on the `#todos` element like this:

    can.$('#todos').html('todo/todos.ejs', [
        { name : 'First Todo' },
        { name : 'Second Todo' }
	]);

__Note:__ You always have to provide the data (second) argument to render a view, otherwise the standard jQuery
modifier will be used. If you have no data to render pass an empty object:

	$('#todos').html('todo/todos.ejs', {});
	// Render todo/todos.ejs wit no data

## Deferreds

Additionally it is also possible to pass a [can.Deferred] as a single parameter to any view modifier. Once
the deferred resolves the result will be rendered using that modifier. This can be used to easily request
and render static content. The following example inserts the content of _content/info.html_ after the `#todos` element:

	can.$('#todos').after(can.ajax({
		url : 'content/info.html'
	}));
