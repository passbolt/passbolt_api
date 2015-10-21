@page can.mustache.context Paths and Contexts
@parent can.mustache.pages 1

When using [can.mustache.Basics tags] in Mustache, the `key` in `[can.mustache.tags.escaped {{key}}]` 
references a property on the current context object. The default context always points to the data 
object initially passed to the template.

Instead of simply referencing a key matching a property on the current context object, a full path can 
be included instead. When a path is found, Mustache will look for a matching property using the entire path:

	Template:
		{{person.name}}

	Data:
		{ 
			person: {
				name: "Austin"
			}
		}

	Result:
		Austin

Additionally, the current context can be changed by using [can.mustache.Sections sections]. Anytime a section 
is opened, any tags inside of it will use that object as the local context for any key lookups:

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

If the key used within a section is not found on the local context, Mustache will look up the 
stack of contexts until it finds a matching key:

	Template:
		{{#person}}
			{{name}} is {{age}}
		{{/person}}

	Data:
		{ 
			person: {
				name: "Austin"
			}
			age: 29
		}

	Result:
		Austin is 29
		