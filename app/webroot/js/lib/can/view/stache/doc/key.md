@typedef {String} can.stache.key key
@parent can.stache.types


A named reference to a value in the [can.view.Scope scope] or 
[can.view.Options options] of a template being rendered.

@option {String}

A key specifies a value in the [can.view.Scope scope] or 
[can.view.Options options] of a template being rendered. The
key is used to look up a value in the scope.

What the key looks like changes the behavior of how a value is looked up in 
the scope. Keys can look like:

 - `{{name}}` - a single property name.
 - `{{name.first}}` - multiple property names.
 - `{{foo\\.bar}}` - a single property name that includes a dot character.
 - `{{./name}}` - looks up a single property in the current context.
 - `{{../name}}` - looks up a single property in the parent context.
 - `{{.}}` or `{{this}}` - looks up the current context.
 - `@index` - The index of a value in an array or [can.List].
 - `@key` - The property name of a value within an object or [can.Map].
 
@body

## Use

A key references a value within the [can.view.Scope scope] of a 
template being rendered. In the following example, the 
key is `name`:

    <h1>{{name}}</h1>
    
If this template is rendered with:

    {
      name: "Austin"
    }

The template writes out:

    <h1>Austin</h1>

A scope is a collection of multiple contexts. By default, a 
key walks up the scope to each context until it finds a value. For example,
a template like:

    {{first}} {{last}}
      {{#children}}
        {{first}} {{last}}
      {{/children}}

Rendered with:

    {
      first: "Barry", last: "Meyer",
      children: [
        {first: "Kim", last: "Sully"},
        {first: "Justin"},
      ]
    }

Writes out:

    Barry Meyer
        Kim Sully
        Justin Meyer

When `last` is looked up on the `{first: "Justin"}` object and not found,
it will then try to read the parent context's `last` property.  This is
why "Justin Meyer" is written out.

## Controlling context lookup

Sometimes, especially with recursive templates, you want to control which
context is used to lookup.  Adding `./` before the key name will 
only look up in the current context. If we change the previous template to:

    {{first}} {{last}}
      {{#children}}
        {{first}} {{./last}}
      {{/children}}

It will write out:

    Barry Meyer
        Kim Sully
        Justin 

Adding `../` before a key will lookup the key starting in the parent 
context.  By changing the previous template to:

    {{first}} {{last}}
      {{#children}}
        {{first}} {{../last}}
      {{/children}}

It will write out:

    Barry Meyer
        Kim Meyer
        Justin Meyer

To write out the current context, write `{{.}}` or `{{this}}`. For example,
a template like:

    {{#each names}}{{.}} {{/each}}

With data like:

    {names: ["Jan","Mark","Andrew"]}

Will write out:

    Jan Mark Andrew 


## @index and @key

When looping over an array or [can.List], you an use `@index` to write out
the index of each property:

    {{#each task}}
      <li>{{@index}} {{name}}</li>
    {{/each}}
    
Indexes start at 0.  If you want to start at 1, you can create a helper like:

    can.stache.registerHelper('@indexNum', function(options){
      return options.scope.attr("@index")+1;
    })

And use it like:

    {{#each task}}
      <li>{{@indexNum}} {{name}}</li>
    {{/each}}

