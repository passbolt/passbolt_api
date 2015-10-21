@page can.mustache.Helpers Helpers
@parent can.mustache.pages 3

# Helpers

Helpers are functions that can be registered and called from within templates. While Mustache is 
intended to be logic-less, helpers enable the execution of logic from within a Mustache template.

Mustache includes a number of built-in helpers, but custom helpers can be registered as well. 
The majority of these built-in helpers have [can.mustache.Basics basic tag] equivalents.

## Built-in Helpers

The `[can.mustache.helpers.section {{#if key}}]` helper is used for **if** statements. The **if** helper is equivalent 
to using a `[can.mustache.helpers.section {{#key}}]` section. If they key passed to the helper is **truthy**, the 
section will be rendered.

	Template: 
		{{#if friends}}
			I have friends!
		{{/if}}

	Data: 
		{
			friends: true
		}

	Result:
		I have friends!

When using the `[can.mustache.helpers.if {{#if key}}]` helper, or any other helper for that matter, 
the special `[can.mustache.helpers.else {{else}}] helper becomes available. `{{else}}` is equivalent to 
an [can.mustache.helpers.inverse {{^key}}] inverse section (rendering **falsey** data), except that it 
only uses a single tag and exists inside 
a helper section.

	Template: 
		<ul>
			{{#if friends}}
				</li>{{name}}</li>
			{{else}}
				<li>No friends.</li>
			{{/if}}
		</ul>

	Data: 
		{
			friends: false
		}

	Result:
		<ul>
			<li>No friends.</li>
		</ul>

The `[can.mustache.helpers.unless {{#unless key}}]` helper is equivalent to using a 
`[can.mustache.helpers.inverse {{^key}}]` inverse section. If they key passed to the helper is **falsey**, the 
section will be rendered.

	Template: 
		{{#unless friends}}
			You don't have any friends!
		{{/unless}}

	Data: 
		{
			friends: []
		}

	Result:
		You don't have any friends!

The `[can.mustache.helpers.each {{#each key}}]` helper is equivalent to using a 
`[can.mustache.helpers.section {{#key}}]` section for iterating an array. In this case, the entire array 
will be rendered using the inner text item by item.

	Template: 
		<ul>
			{{#each friends}}
				<li>{{name}}</li>
			{{/each}}
		</ul>

	Data: 
		{ 
			friends: [ 
				{ name: "Austin" }, 
				{ name: "Justin" } 
			] 
		}

	Result:
		<ul>
			<li>Austin</li>
			<li>Justin</li>
		</ul>

The `[can.mustache.helpers.with {{#with key}}]` helper is equivalent to using a 
`[can.mustache.helpers.section {{#key}}]` section for regular objects. The helper will change 
the current context so that all tags inside will look for keys on the local context first.

	Template: 
		<h1>Hi {{name}}</h1>
		{{#with friend}}
			<p>You have a new friend: {{name}}</p>
		{{/with}}

	Data: 
		{
			name: "Andy",
			friend: { name: "Justin" } 
		}

	Result:
		<h1>Hi Austin</h1>
		<p>You have a new friend: Justin</p>

When using the `[can.mustache.helpers.is {{#is expr1 expr2}}]` helper you can simply compare 
results of passed arguments. If the result of comparsion is **truthy**, the section will be rendered.

	Template: 
		<ul>
			{{#is name 'Alex'}}
				</li>Your name is {{name}}</li>
			{{else}}
				<li>Your name is not Alex!</li>
			{{/if}}
		</ul>

	Data: 
		{
			name: 'John'
		}

	Result:
		<ul>
			<li>Your name is not Alex!</li>
		</ul>

The `[can.mustache.helpers.elementCallback {{(el)->CODE}}]` helper is a special helper for element callbacks that 
will pass the active DOM element within the template to a function made up of inline code. This 
is most useful for tasks such as initializing a jQuery plugin on the new HTML.

	<div class="tabs" {{(el) -> el.jquery_tabs()}}></div>

The `[can.mustache.helpers.data {{data key}}]` helper is another special helper for data associations that 
will save the current context on the active DOM element with [can.data].

	Template:
		<ul>
			<li id="personli" {{data 'person'}}>{{name}}</li>
		</ul>

	Data:
		{
			name: 'Austin'
		}

	The data can be retrieved later with:
		var nameObject = can.data(can.$('#personli'), 'person'); 

## Registering Helpers

You can register your own global helper with the `[can.mustache.registerHelper registerHelper]` method, or 
a local helper (just accessible by the template being rendered) by passing in an object containing helper functions to [can.view].

Localization is a good example of a custom helper you might implement
in your application. The below example takes a given key and 
returns the localized value using 
[jQuery Globalize](https://github.com/jquery/globalize).

	Mustache.registerHelper('l10n', function(str, options){
		return Globalize != undefined 
			? Globalize.localize(str) 
			: str;
	});

Or another way to do this:

	can.view("//path/to/template.mustache", data, {
		l10n: function(str, options){
			return Globalize != undefined 
				? Globalize.localize(str) 
				: str;
		}
	})

In the template, invoke the helper by calling the helper
name followed by any additional arguments.
	
	Template:
		<span>{{l10n 'mystring'}}</span>

	Result:
		<span>my string localized</span>

__Helpers with can.Map attributes__

If a can.Map attribute is passed as an argument to a helper, it is converted to a can.compute getter/setter function.  This is to allow creating 2-way binding type functionality between a can.Map attribute and a form element. For example in your template:

	<div>{{addPrefix name}}</div>

Your helper would look like:

	var item = new can.Map({name: "Brian"}),
	    frag = can.view("#template", item, {
	      addPrefix: function(name){
	        return "Mr." + name()
	      }
	    });

Note we're calling `name()` in order to read its contents.

__Multiple Arguments__

You can pass multiple arguments just by putting a space between
that and the previous argument like so:

	{{helper 'cat' 'hat'}}

	Mustache.registerHelper('helper', function(arg1, arg2, options){
		// arg1 -> 'cat'
		// arg2 -> 'hat'
	});

__Evaluating Helpers__

If you want to use a helper with a [can.mustache.Sections section], you need to call
`options.fn(context)` in your return statement. This will return a 
string with the resulting evaluated [can.mustache.Sections section].

Similarly, you can call `options.inverse(context)` to evaluate the 
template between an `{{else}}` tag and the closing tag.

For example, when a route matches the string passed to our
routing helper it will show/hide the text.

	Mustache.registerHelper('routing', function(str, options){
		if (can.route.attr('filter') === str)
			return options.fn(this);
		}
	});

	{{#routing 'advanced'}}
		You have applied the advanced filter.
	{{/routing}}
	
__Advanced Helpers__

Helpers can be passed normal objects, native objects like numbers and strings, 
as well as a hash object. The hash object will be an object literal containing 
all ending arguments using the `key=value` syntax. The hash object will be provided 
to the helper as `options.hash`. Additionally, when using [can.mustache.Sections section] with the helper, 
you can set a custom context by passing the object instead of `this`.

	Mustache.registerHelper('exercise', function(group, action, 
											num, options){
		if (group && group.length > 0 && action && num > 0) {
			return options.fn({
				group: group,
				action: action,
				where: options.hash.where,
				when: options.hash.when,
				num: num
			});
		}
		else {
			return options.inverse(this);
		}
	});

	{{#exercise pets 'walked' 3 where='around the block' when=time}}
		Along with the {{#group}}{{.}}, {{/group}}
		we {{action}} {{where}} {{num}} times {{when}}.
	{{else}}
		We were lazy today.
	{{/exercise}}
	
	{
		pets: ['cat', 'dog', 'parrot'],
		time: 'this morning'
	}
	
This would output:

	Along with the cat, dog, parrot, we walked around the block 
	3 times this morning.
	
Whereas an empty data object would output:

	We were lazy today.
