@function can.view.bindings.can-EVENT can-EVENT
@parent can.view.bindings

@signature `can-EVENT='{methodKey [argKey..] [hashName=hashKey...]}'`

Specify a callback function to be called on a particular event. You can create your own special event types.

@param {String} EVENT A event name like `click` or `keyup`.  If you are
using jQuery, you can listen to jQuery special events too.

@param {can.stache.key} methodKey A named key value in the current scope.  The value
should be a function.

@param {can.stache.key} [argKey...] Key values that will be passed as
arguments to the `methodKey` function value.  Key values can 
be read from the scope, or literals like `"foo"`, `1`, etc.

The following key values are also supported:

 - `@element` - The [can.$] wrapped element the event happened upon.
 - `@event` - The event object.
 - `@viewModel` - If the element is a [can.Component], the component's [can.Component::viewModel viewModel].
 - `@context` - The current context.
 - `@scope` - The current [can.view.Scope].

If no `argKey`s or `hashKey`s are provided, the methodKey will be called with the
`@context`, `@element` and `@event` as arguments.

@param {String} hashName A property name 
that gets added to the `hash` argument.  The hash argument is the
last value passed to the function specified by `methodKey`.

@param {can.stache.key} hashKey Specifies value that is added 
to the `hash` argument for a `hashName` value.  `hashKey` supports
the same key values as `argKey`.

@body


## Use

By adding `can-EVENT='methodKey'` to an element, the function pointed to
by `methodKey` is bound to the element's `EVENT` event. The function can be
passed any number of arguments from the surrounding scope, or `name=value`
attributes for named arguments. Direct arguments will be provided to the
handler in the order they were given, except `name=value` arguments, which
will all be given as part of a `hash` argument inserted after all direct
arguments.

If no `argKey`s or `hashKey`s are provided, the methodKey will be called with the
`@content`, `@element` and `@event` as arguments.

The following uses `can-click={items.splice @index 1}` to remove a
item from `items` when that item is clicked on.

@demo can/view/bindings/doc/can-event-args.html

## Special Event Types

can.view.bindings supports creating special event types 
(events that aren't natively triggered by the DOM), which are 
bound by adding attributes like `can-SPECIAL='KEY'`. This is 
similar to [$.special](http://benalman.com/news/2010/03/jquery-special-events/).

### can-enter

can-enter is a special event that calls its handler whenever the enter 
key is pressed while focused on the current element. For example: 

	<input type='text' can-enter='{save}' />

The above template snippet would cause the save method 
(in the [can.mustache Mustache] [can.view.Scope scope]) whenever 
the user hits the enter key on this input.
