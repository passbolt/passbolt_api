@page can.stache.context Paths and Contexts
@parent can.stache.pages 1

When using [can.stache.Basics tags] in Stache, the `key` in `[can.stache.tags.escaped {{key}}]` 
references a property on the current context object. The default context always points to the data 
object initially passed to the template.

Instead of simply referencing a key matching a property on the current context object, a full path can 
be included instead. When a path is found, Stache will look for a matching property using the entire path:

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

Additionally, the current context can be changed by using [can.stache.Sections sections]. Anytime a section 
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

If the key used within a section is not found on the local context, Stache will look up the 
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
		