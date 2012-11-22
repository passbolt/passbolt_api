@page Helpers
@parent can.Mustache 4

# Helpers

Helpers allow you to register functions that can be called 
from any context in a template. 

Mustache includes a number of built-in helpers that are listed below
but you can register your own helper too.

## if

In addition to truthy/falsey evaluation with sections, you can use an 
explicit `if` condition to render a block.

	{
		friends: true
	}

	{{#if friends}}
		I have friends!
	{{/if}}

would render:

	I have friends!
	
`if` acts similarly to a truthy `{{#section}}`.

## else

When using `if` or a custom helper, you can specify the inverse
of the evaluation by using the `else` helper.

	{
		friend: false
	}

	<ul>
		{{#if friends}}
			</li>{{name}}</li>
		{{else}}
			<li>No friends.</li>
		{{/if}}
	</ul>

would render:

	<ul>
		<li>No friends.</li>
	</ul>

`else` acts similarly to a falsey `{{^inverse}}`, but only applies when used within another helper.

In this case, using the `if`/`else` helpers simplify your template by not requiring extra sections to be specified.

## unless

The `unless` helper evaluates the inverse of the value of the key and renders 
the block between the helper and the slash.

	{
		friends: []
	}

	{{#unless friends}}
		You don't have any friends!
	{{/unless}}

would render:

	You don't have any friends!
	
`unless` acts similarly to a falsey `{{^inverse}}`.

## each

The`each` helper explicitly iterates over an array of items and
renders the block.

Like sections, it will reset the current context to the value for which its iterating.
See the [basics of contexts](#Basics) for more information.

	{ 
		friends: [ 
			{ name: "Austin" }, 
			{ name: "Justin" } 
		] 
	}

	<ul>
		{{#each friends}}
			<li>{{name}}</li>
		{{/each}}
	</ul>

would render:

	<ul>
		<li>Austin</li>
		<li>Justin</li>
	</ul>

## with

Mustache typically applies the context passed in the section at runtime.  However,
you can override this context by using the `with` helper.

For example, using the `with` helper Mustache shifts the context to the friends object.

	{
		name: "Austin"
		friends: 1
	}


	<h1>Hi {{name}}</h1>
	{{#with friends}}
		<p>You have {{.}} new friend!</p>
	{{/with}}

would render:

	<h1>Hi Austin</h1>
	<p>You have 1 new friend!</p>

## Element Callbacks

When rendering HTML with views, you often want to call some JavaScript
such as intializing a jQuery plugin on the new HTML.

Mustache makes it easy to define this code in the markup.  Using the
[ES5 Arrow Syntax](http://wiki.ecmascript.org/doku.php?id=strawman:arrow_function_syntax) 
its easy to define the element for which is passed followed by the arrow
and the function to execute on the element.

	<div class="tabs" {{(el) -> el.jquery_tabs()}}></div>

After rendering the HTML, `jquery_tabs` will be called on the tabs div.

## Data Associations

Attaching data to an element is done by calling the `data` helper
followed by the attribute name you want to attach it as.

	{
		name: 'Austin'
	}

	<ul>
		<li id="personli" {{data 'person'}}>{{name}}</li>
	</ul>

Now the data can be access by doing:

	var nameObject = can.data(can.$('#personli'), 'person');

It automatically attaches the data to the
element using [can.data] and the implied context of `this`.

## Registering Helpers

You can register your own helper with the `Mustache.registerHelper` method.

Localization is a good example of a custom helper you might implement
in your application. The below example takes a given key and 
returns the localized value using 
[jQuery Globalize](https://github.com/jquery/globalize).

	Mustache.registerHelper('l10n', function(str, options){
		return Globalize != undefined 
			? Globalize.localize(str) 
			: str;
	});

In the template, invoke the helper by calling the helper
name followed by any additional arguments.

	<span>{{l10n 'mystring'}}</span>

will render:

	<span>my string localized</span>

__Multiple Arguments__

You can pass multiple arguments just by putting a space between
that and the previous argument like so:

	{{helper 'cat' 'hat'}}

	Mustache.registerHelper('helper', function(arg1, arg2, options){
		// arg1 -> 'cat'
		// arg2 -> 'hat'
	});

__Evaluating Helpers__

If you want to use a helper with a section, you need to call 
`options.fn(context)` in your return statement. This will return a 
string with the resulting evaluated section.

Similarly, you can call `options.inverse(context)` to evaluate the 
template between an `{{else}}` magic tag and the closing magic tag.

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
to the helper as `options.hash`. Additionally, when using sections with the helper, 
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
	
Whereas, an empty data object would output:

	We were lazy today.
