@function can.stache.helpers.data {{data name}}
@parent can.stache.htags 7
@signature `{{data name[ key]}}`

Adds the current [can.stache.context context] to the
element's [can.data].

@param {String} name The name of the data attribute to use for the
context.

@body

## Use

It is common to want some data in the template to be available
on an element.  `{{data name}}` allows you to save the
context so it can later be retrieved by [can.data] or
`$.fn.data`. 

<a class="jsbin-embed" href="http://jsbin.com/juxem/latest/embed?html,js,output">JS Bin</a><script src="http://static.jsbin.com/js/embed.js"></script>

### Getting more specific

By passing a key name as the second argument to the data helper, you can specify which data is used: `{{data name key}}`.

<a class="jsbin-embed" href="http://jsbin.com/munuco/latest/embed?html,js,output">JS Bin</a><script src="http://static.jsbin.com/js/embed.js"></script>