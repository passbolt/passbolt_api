@function can.Map.prototype.attr attr
@parent can.Map.prototype 2

@description Get or set properties on a Map.

@signature `map.attr()`

Gets a collection of all the properties in this `can.Map`.

@return {Object} an object with all the properties in this `can.Map`.

@signature `map.attr(key)`

Reads a property from this `can.Map`.

@param {String} key the property to read
@return {*} the value assigned to _key_.

@signature `map.attr(key, value)`

Assigns _value_ to a property on this `can.Map` called _key_.

@param {String} key the property to set
@param {*} the value to assign to _key_.
@return {can.Map} this Map, for chaining

@signature `map.attr(obj[, removeOthers])`

Assigns each value in _obj_ to a property on this `can.Map` named after the
corresponding key in _obj_, effectively merging _obj_ into the Map.

@param {Object} obj a collection of key-value pairs to set.
If any properties already exist on the `can.Map`, they will be overwritten.

@param {bool} [removeOthers=false] whether to remove keys not present in _obj_.
To remove keys without setting other keys, use `[can.Map::removeAttr removeAttr]`.

@return {can.Map} this Map, for chaining

@body
`attr` gets or sets properties on the `can.Map` it's called on. Here's a tour through
how all of its forms work:


    var people = new can.Map({});

    // set a property:
    people.attr('a', 'Alex');

    // get a property:
    people.attr('a'); // 'Alex'

    // set and merge multiple properties:
    people.attr({
        a: 'Alice',
        b: 'Bob'
    });

    // get all properties:
    people.attr(); // {a: 'Alice', b: 'Bob'}

    // set properties while removing others:
    people.attr({
        b: 'Bill',
        e: 'Eve'
    }, true);

    people.attr(); // {b: 'Bill', e: 'Eve'}


## Deep properties

`attr` can also set and read deep properties. All you have to do is specify
the property name as you normally would if you weren't using `attr`.


    var people = new can.Map({names: {}});

    // set a property:
    people.attr('names.a', 'Alice');

    // get a property:
    people.attr('names.a'); // 'Alice'
    people.names.attr('a'); // 'Alice'

    // get all properties:
    people.attr(); // {names: {a: 'Alice'}}


Objects that are added to Observes become Observes themselves behind the scenes,
so changes to deep properties fire events at each level, and you can bind at any
level. As this example shows, all the same events are fired no matter what level
you call `attr` at:


    var people = new can.Map({names: {}});

    people.bind('change', function(ev, attr, how, newVal, oldVal) {
        console.log('people change: ' + attr + ', ' + how + ', ' + newVal + ', ' + oldVal);
    });

    people.names.bind('change', function(ev, attr, how, newVal, oldVal) {
        console.log('people.names change' + attr + ', ' + how + ', ' + newVal + ', ' + oldVal);
    });

    people.bind('names', function(ev, newVal, oldVal) {
        console.log('people names: ' + newVal + ', ' + oldVal);
    });

    people.names.bind('a', function(ev, newVal, oldVal) {
        console.log('people.names a: ' + newVal + ', ' + oldVal);
    });

    people.bind('names.a', function(ev, newVal, oldVal) {
        console.log('people names.a: ' + newVal + ', ' + oldVal);
    });

    people.attr('names.a', 'Alice'); // people change: names.a, add, Alice, undefined
                                  // people.names change: a, add, Alice, undefined
                                  // people.names a: Alice, undefined
                                  // people names.a: Alice, undefined

    people.names.attr('b', 'Bob');   // people change: names.b, add, Bob, undefined
                                  // people.names change: b, add, Bob, undefined
                                  // people.names b: Bob, undefined
                                  // people names.b: Bob, undefined


## Properties with dots in their name

As shown above, `attr` enables reading and setting deep properties so special care must be taken when property names include dots '`.`'. To read a property containing dots, escape each one using '`\`'. This prevents `attr` from performing a deep lookup and throwing an error when the deep property is not found.

```
var person = new can.Map({
	'first.name': 'Alice'
});

person.attr('first.name'); // throws Error
person.attr('first\.name'); // 'Alice'

```

When setting a property containing dots, pass an object to `attr` containing the property name and new value. Setting a property by passing a string to `attr` will attempt to set a deep property and will throw an error.

```
var person = new can.Map({
	'first.name': 'Alice'
});

person.attr('first.name', 'Bob'); // throws Error
person.attr('first\.name', 'Bob'); // throws Error
person.attr({'first.name': 'Bob'}); // Works

```

## See also

For information on the events that are fired on property changes and how
to listen for those events, see [can.Map.prototype.bind bind].
