@function can.stache.tags.escaped {{key}}

@description Insert the value of the [can.stache.key key] into the
output of the template.

@parent can.stache.tags 0

@signature `{{key}}`

@param {can.stache.key} key A key that references one of the following:

 - A [can.stache.registerHelper registered helper].
 - A value within the current or parent
   [can.stache.context context]. If the value is a function or [can.compute], the
   function's return value is used.

@return {String|Function|*}

After the key's value is found (and set to any function's return value),
it is passed to [can.view.txt] as the result of a call to its `func`
argument. There, if the value is a:

 - `null` or `undefined` - an empty string is inserted into the rendered template result.
 - `String` or `Number` - the value is inserted into the rendered template result.
 - `Function` - A [can.view.hook hookup] attribute or element is inserted so this function
   will be called back with the DOM element after it is created.

@body

## Use

`{{key}}` insert data into the template. It most commonly references
values within the current [can.stache.context context]. For example:

Rendering:

    <h1>{{name}}</h1>

With:

    {name: "Austin"}

Results in:

    <h1>Austin</h1>

If the key value is a String or Number, it is inserted into the template.
If it is `null` or `undefined`, nothing is added to the template.


## Nested Properties

Stache supports nested paths, making it possible to
look up properties nested deep inside the current context. For example:

Rendering:

    <h1>{{book.author}}</h1>

With:

    {
      book: {
        author: "Ernest Hemingway"
      }
    }

Results in:

    <h1>Ernest Hemingway</h1>

## Looking up values in parent contexts

Sections and block helpers can create their own contexts. If a key's value
is not found in the current context, it will look up the key's value
in parent contexts. For example:

Rendering:

    {{#chapters}}
       <li>{{title}} - {{name}}</li>
    {{chapters}}

With:

    {
      title: "The Book of Bitovi"
      chapters: [{name: "Breakdown"}]
    }

Results in:

    <li>The Book of Bitovi - Breakdown</li>

