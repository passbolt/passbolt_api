@function can.stache.helpers.helper {{helper args hashes}}
@parent can.stache.htags 0

@description Calls a stache helper function and inserts its return value into
the rendered template.

@signature `{{helper [args...] [hashProperty=hashValue...]}}`

Calls a stache helper function or a function. For example:

The template:

    <p>{{madLib "Lebron James" verb 4 foo="bar"}}</p>

Rendered with:

    {verb: "swept"}

Will call a `madLib` helper with the following arguements:

    can.stache.registerHelper('madLib',
      function(subject, verb, number, options){
        // subject -> "Lebron James"
        // verb -> "swept"
        // number -> 4
        // options.hash.foo -> "bar"
    });

@param {can.stache.key} helper A key that finds a [can.stache.helper helper function]
that is either [can.stache.registerHelper registered] or found within the
current or parent [can.stache.context context].

@param {...can.stache.key|String|Number} [args] Space seperated arguments
that get passed to the helper function as arguments. If the key's value is a:

 - [can.Map] - A getter/setter [can.compute] is passed.
 - [can.compute] - The can.compute is passed.
 - `function` - The function's return value is passed.

@param {String} hashProperty

A property name that gets added to a [can.stache.helperOptions helper options]'s
hash object.

@param {...can.stache.key|String|Number} hashValue A value that gets
set as a property value of the [can.stache.helperOptions helper option argument]'s
hash object.

@body

## Use

The `{{helper}}` syntax is used to call out to stache [can.stache.helper helper functions] functions
that may contain more complex functionality. `helper` is a [can.stache.key key] that must match either:

 - a [can.stache.registerHelper registered helper function], or
 - a function in the current or parent [can.stache.context contexts]

The following example shows both cases.

The Template:

    <p>{{greeting}} {{user}}</p>

Rendered with data:

    {
      user: function(){ return "Justin" }
    }

And a with a registered helper like:

    can.stache.registerHelper('greeting', function(){
      return "Hello"
    });

Results in:

    <p>Hello Justin</p>

## Arguments

Arguments can be passed from the template to helper function by
listing space seperated strings, numbers or other [can.stache.key keys] after the
`helper` name.  For example:

The template:

    <p>{{madLib "Lebron James" verb 4}}</p>

Rendered with:

    {verb: "swept"}

Will call a `madLib` helper with the following arguements:

    can.stache.registerHelper('madLib',
      function(subject, verb, number, options){
        // subject -> "Lebron James"
        // verb -> "swept"
        // number -> 4
    });

If an argument `key` value is a [can.Map] property, the Observe's
property is converted to a getter/setter [can.compute]. For example:

The template:

    <p>What! My name is: {{mr user.name}}</p>

Rendered with:

    {user: new can.Map({name: "Slim Shady"})}

Needs the helper to check if name is a function or not:

    can.stache.registerHelper('mr',function(name){
      return "Mr. "+ (typeof name === "function" ?
                      name():
                      name)
    })

This behavior enables two way binding helpers and is explained in more detail
on the [can.stache.helper helper functions] docs.

## Hash

If enumerated arguments isn't an appropriate way to configure the behavior
of a helper, it's possible to pass a hash of key-value pairs to the
[can.stache.helperOptions helper option argument]'s
hash object.  Properties and values are specified
as `hashProperty=hashValue`.  For example:

The template:

    <p>My {{excuse who=pet how="shreded"}}</p>
`
And the helper:

    can.stache.registerHelper("excuse",function(options){
      return ["My",
        options.hash.who || "dog".
        options.hash.how || "ate",
        "my",
        options.hash.what || "homework"].join(" ")
    })

Render with:

    {pet: "cat"}

Results in:

    <p>My cat shareded my homework</p>

## Returning an element callback function

If a helper returns a function, that function is called back after
the template has been rendered into DOM elements. This can
be used to create stache tags that have rich behavior. Read about it
on the [can.stache.helper helper function] page.

