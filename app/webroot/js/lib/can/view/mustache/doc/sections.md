@page Sections
@parent can.Mustache 1

# Sections

Sections contain text blocks and evaluate whether to render it or not.  If
the object evaluates to an array it will iterate over it and render the block
for each item in the array.  There are four different types of sections.

## Falseys or Empty Arrays

If the value returns a `false`, `undefined`, `null`, `""` or `[]` we consider
that a *falsey* value.

If the value is falsey, the section will **NOT** render the block.

	{ 
		friends: false
	}

	{{#friends}}
		Never shown!
	{{/friends}}


## Arrays

If the value is a non-empty array, sections will iterate over the 
array of items, rendering the items in the block.

For example, a list of friends will iterate
over each of those items within a section.

	{ 
		friends: [ 
			{ name: "Austin" }, 
			{ name: "Justin" } 
		] 
	}

	<ul>
		{{#friends}}
			<li>{{name}}</li>
		{{/friends}}
	</ul>

would render:

	<ul>
		<li>Austin</li>
		<li>Justin</li>
	</ul>

Reminder: Sections will reset the current context to the value for which its iterating.
See the [basics of contexts](#Basics) for more information.

## Truthys

When the value is a non-falsey object but not a list, it is considered truthy and will be used 
as the context for a single rendering of the block.

	{
		friends: { name: "Jon" }
	}

	{{#friends}}
		Hi {{name}}
	{{/friends}}

would render:

	Hi Jon!

## Inverted

Inverted sections match falsey values. An inverted section 
syntax is similar to regular sections except it begins with a caret 
rather than a pound. If the value referenced is falsey, the section will render.

	{
		friends: []
	}

	<ul>
		{{#friends}}
			</li>{{name}}</li>
		{{/friends}}
		{{^friends}}
			<li>No friends.</li>
		{{/friends}}
	</ul>

would render:

	<ul>
		<li>No friends.</li>
	</ul>


## Comments

Comments, which do not appear in template output, begin a bang (!).

	<h1>My friend is {{!Brian}}</h1>

would render:

	<h1>My friend is </h1>