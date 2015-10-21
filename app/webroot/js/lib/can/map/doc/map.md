@constructor can.Map
@inherits can.Construct
@parent canjs
@group can.Map.prototype 0 Prototype
@group can.Map.static 1 Static
@group can.Map.plugins 2 Plugins
@test can/map/test.html
@plugin can/map
@release 2.0
@link ../docco/map/map.html docco

@description Create observable objects.


@signature `new can.Map([props])`

Creates a new instance of can.Map.

@param {Object} [props] Properties and values to seed the Observe with.
@return {can.Map} An instance of `can.Map` with the properties from _props_.

@signature `can.Map.extend([name,] [staticProperties,] instanceProperties)`

Creates a new extended constructor function. 


@body

## Use

Watch this video to see an example of creating an ATM machine using can.Map:

<iframe width="662" height="372" src="https://www.youtube.com/embed/QP9mHyxZNiI" frameborder="0" allowfullscreen></iframe>


`can.Map` provides a way for you to listen for and keep track of changes
to objects. When you use the getters and setters provided by `can.Map`,
events are fired that you can react to. `can.Map` also has support for
working with deep properties. Observable arrays are also available with
`[can.List]`, which is based on `can.Map`.

## Working with Observes

To create an Observe, use `new can.Map([props])`. This will return a
copy of `props` that emits events when its properties are changed with
`[can.Map.prototype.attr attr]`.

You can read the values of properties on Observes directly, but you should
never set them directly. You can also read property values using `attr`.


    var aName = {a: 'Alexis'},
        map = new can.Map(aName);

    // Observes are copies of data:
    aName === map; // false

    // reading from an Observe:
    map.attr();    // {a: 'Alexis'}
    map.a;         // 'Alexis'
    map.attr('a'); // 'Alexis'

    // setting an Observe's property:
    map.attr('a', 'Alice');
    map.a; // Alice

    // removing an Observe's property;
    map.removeAttr('a');
    map.attr(); // {}

    // Don't do this!
    map.a = 'Adam'; // wrong!


Find out more about manipulating properties of a map under
[can.Map.prototype.attr attr] and [can.Map.prototype.removeAttr removeAttr].

## Listening to changes

The real power of maps comes from being able to react to
properties being added, set, and removed. Maps emit events when
properties are changed that you can bind to.

`can.Map` has two types of events that fire due to changes on a map:
- the _change_ event fires on every change to a map.
- an event named after the property name fires on every change to that property.


    var o = new can.Map({});
    o.bind('change', function(ev, attr, how, newVal, oldVal) {
        console.log('Something on o changed.');
    });
    o.bind('a', function(ev, newVal, oldVal) {
        console.log('a was changed.');
    });

    o.attr('a', 'Alexis'); // 'Something on o changed.'
                           // 'a was changed.'
    o.attr({
        'a': 'Alice',      // 'Something on o changed.' (for a's change)
        'b': 'Bob'         // 'Something on o changed.' (for b's change)
    });                    // 'a was changed.'

    o.removeAttr('a');     // 'Something on o changed.'
                           // 'a was changed.'


For more detail on how to use these events, see [can.Map.prototype.bind bind] and
[can.Map.prototype.unbind unbind]. There is also a plugin called [can.Map.delegate]
that makes binding to specific types of events easier:


    var o = new can.Map({});
    o.delegate('a', 'add', function(ev, newVal, oldVal) {
        console.log('a was added.');
    });
    o.delegate('a', 'set', function(ev, newVal, oldVal) {
        console.log('a was set.');
    });
    o.delegate('a', 'remove', function(ev, newVal, oldVal) {
        console.log('a was removed.');
    });
    o.delegate('a', 'change', function(ev, newVal, oldVal) {
        console.log('a was changed.');
    });

    o.attr('a', 'Alexis'); // 'a was added.'
                           // 'a was changed.'

    o.attr('a', 'Alice'); // 'a was set.'
                          // 'a was changed.'

    o.removeAttr('a'); // 'a was removed.'
                       // 'a was changed.'

