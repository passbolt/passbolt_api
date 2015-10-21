@function can.Map.prototype.bind bind
@parent can.Map.prototype 3

@description Bind event handlers to a Map.

@signature `map.bind(eventType, handler)`

@param {String} eventType the type of event to bind this handler to
@param {Function} handler the handler to be called when this type of event fires
The signature of the handler depends on the type of event being bound. See below
for details.
@return {can.Map} this Map, for chaining

@body
`bind` binds event handlers to property changes on `can.Map`s. When you change
a property using `attr`, two events are fired on the Map, allowing other parts
of your application to map the changes to the object.

## The _change_ event

The first event that is fired is the _change_ event. The _change_ event is useful
if you want to react to all changes on a Map.


    var o = new can.Map({});
    o.bind('change', function(ev, attr, how, newVal, oldVal) {
        console.log('Something changed.');
    });


The parameters of the event handler for the _change_ event are:

- _ev_ The event object.
- _attr_ Which property changed.
- _how_ Whether the property was added, removed, or set. Possible values are `'add'`, `'remove'`, or `'set'`.
- _newVal_ The value of the property after the change. `newVal` will be `undefined` if the property was removed.
- _oldVal_ This is the value of the property before the change. `oldVal` will be `undefined` if the property was added.

Here is a concrete tour through the _change_ event handler's arguments:


    var o = new can.Map({});
    o.bind('change', function(ev, attr, how, newVal, oldVal) {
        console.log(ev + ', ' + attr + ', ' + how + ', ' + newVal + ', ' + oldVal);
    });

    o.attr('a', 'Alexis'); // [object Object], a, add, Alexis, undefined
    o.attr('a', 'Adam');   // [object Object], a, set, Adam, Alexis
    o.attr({
        'a': 'Alice',      // [object Object], a, set, Alice, Adam
        'b': 'Bob'         // [object Object], b, add, Bob, undefined
    });
    o.removeAttr('a');     // [object Object], a, remove, undefined, Alice


(See also `[can.Map::removeAttr removeAttr]`, which removes properties).

## The _property name_ event

The second event that is fired is an event whose type is the same as the changed
property's name. This event is useful for noticing changes to a specific property.


    var o = new can.Map({});
    o.bind('a', function(ev, newVal, oldVal) {
        console.log('The value of a changed.');
    });


The parameters of the event handler for the _property name_ event are:

- _ev_ The event object.
- _newVal_ The value of the property after the change. `newVal` will be `undefined` if the property was removed.
- _oldVal_ The value of the property before the change. `oldVal` will be `undefined` if the property was added.

Here is a concrete tour through the _property name_ event handler's arguments:


    var o = new can.Map({});
    o.bind('a', function(ev, newVal, oldVal) {
        console.log(ev + ', ' + newVal + ', ' + oldVal);
    });

    o.attr('a', 'Alexis'); // [object Object], Alexis, undefined
    o.attr('a', 'Adam');   // [object Object], Adam, Alexis
    o.attr({
        'a': 'Alice',      // [object Object], Alice, Adam
        'b': 'Bob'
    });
    o.removeAttr('a');     // [object Object], undefined, Alice


## See also

More information about changing properties on Observes can be found under
[can.Map.prototype.attr attr].

For a more specific way to changes on Observes, see the [can.Map.delegate] plugin.
*/