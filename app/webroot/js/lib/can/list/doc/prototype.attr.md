@page can.List.prototype.attr attr
@parent can.List.prototype

@description Get or set elements in a List.
@function can.List.prototype.attr attr

@signature `list.attr()`

Gets an array of all the elements in this `can.List`.

@return {Array} An array with all the elements in this List.

@signature `list.attr(index)`

Reads an element from this `can.List`.

@param {Number} index The element to read.
@return {*} The value at _index_.

@signature `list.attr(index, value)`

Assigns _value_ to the index _index_ on this `can.List`, expanding the list if necessary.

@param {Number} index The element to set.
@param {*} value The value to assign at _index_.
@return {can.List} This list, for chaining.

@signature `list.attr(elements[, replaceCompletely])`

Merges the members of _elements_ into this List, replacing each from the beginning in order. If _elements_ is longer than the current List, the current List will be expanded. If _elements_ is shorter than the current List, the extra existing members are not affected (unless _replaceCompletely_ is `true`). To remove elements without replacing them, use `[can.Map::removeAttr removeAttr]`.

@param {Array} elements An array of elements to merge in.

@param {bool} [replaceCompletely=false] whether to completely replace the elements of List

If _replaceCompletely_ is `true` and _elements_ is shorter than the List, the existing extra members of the List will be removed.

@return {can.List} This list, for chaining.

@body


## Use

`attr` gets or sets elements on the `can.List` it's called on. Here's a tour through how all of its forms work:

     var people = new can.List(['Alex', 'Bill']);

     // set an element:
     people.attr(0, 'Adam');

     // get an element:
     people.attr(0); // 'Adam'
     people[0]; // 'Adam'

     // get all elements:
     people.attr(); // ['Adam', 'Bill']

     // extend the array:
     people.attr(4, 'Charlie');
     people.attr(); // ['Adam', 'Bill', undefined, undefined, 'Charlie']

     // merge the elements:
     people.attr(['Alice', 'Bob', 'Eve']);
     people.attr(); // ['Alice', 'Bob', 'Eve', undefined, 'Charlie']

## Deep properties

`attr` can also set and read deep properties. All you have to do is specify the property name as you normally would if you weren't using `attr`.

```
var people = new can.List([{name: 'Alex'}, {name: 'Bob'}]);

// set a property:
people.attr('0.name', 'Alice');

// get a property:
people.attr('0.name');  // 'Alice'
people[0].attr('name'); // 'Alice'

// get all properties:
people.attr(); // [{name: 'Alice'}, {name: 'Bob'}]
```

The discussion of deep properties under `[can.Map.prototype.attr]` may also be enlightening.

## Events

`can.List`s emit five types of events in response to changes. They are:

- the _change_ event fires on every change to a List.
- the _set_ event is fired when an element is set.
- the _add_ event is fired when an element is added to the List.
- the _remove_ event is fired when an element is removed from the List.
- the _length_ event is fired when the length of the List changes.

### The _change_ event

The first event that is fired is the _change_ event. The _change_ event is useful
if you want to react to all changes on an List.

```
var list = new can.List([]);
list.bind('change', function(ev, index, how, newVal, oldVal) {
    console.log('Something changed.');
});
```

The parameters of the event handler for the _change_ event are:

- _ev_ The event object.
- _index_ Where the change took place.
- _how_ Whether elements were added, removed, or set.
 Possible values are `'add'`, `'remove'`, or `'set'`.
- _newVal_ The elements affected after the change
 _newVal_ will be a single value when an index is set, an Array when elements
were added, and `undefined` if elements were removed.
- _oldVal_ The elements affected before the change.
_newVal_ will be a single value when an index is set, an Array when elements
were removed, and `undefined` if elements were added.

Here is a concrete tour through the _change_ event handler's arguments:

```
var list = new can.List();
list.bind('change', function(ev, index, how, newVal, oldVal) {
    console.log(ev + ', ' + index + ', ' + how + ', ' + newVal + ', ' + oldVal);
});

list.attr(['Alexis', 'Bill']); // [object Object], 0, add, ['Alexis', 'Bill'], undefined
list.attr(2, 'Eve');           // [object Object], 2, add, Eve, undefined
list.attr(0, 'Adam');          // [object Object], 0, set, Adam, Alexis
list.attr(['Alice', 'Bob']);   // [object Object], 0, set, Alice, Adam
                               // [object Object], 1, set, Bob, Bill
list.removeAttr(1);            // [object Object], 1, remove, undefined, Bob
```

### The _set_ event

_set_ events are fired when an element at an index that already exists in the List is modified. Actions can cause _set_ events to fire never also cause _length_ events to fire (although some functions, such as `[can.List.prototype.splice splice]` may cause unrelated sets of events to fire after being batched).

The parameters of the event handler for the _set_ event are:

- _ev_ The event object.
- _newVal_ The new value of the element.
- _index_ where the set took place.

Here is a concrete tour through the _set_ event handler's arguments:

```
var list = new can.List();
list.bind('set', function(ev, newVal, index) {
    console.log(newVal + ', ' + index);
});

list.attr(['Alexis', 'Bill']);
list.attr(2, 'Eve');
list.attr(0, 'Adam');          // Adam, 0
list.attr(['Alice', 'Bob']);   // Alice, 0
                               // Bob, 1
list.removeAttr(1);
```

### The _add_ event

_add_ events are fired when elements are added or inserted
into the List.

The parameters of the event handler for the _add_ event are:

- _ev_ The event object.
- _newElements_ The new elements.
 If more than one element is added, _newElements_ will be an array. Otherwise, it is simply the new element itself.
- _index_ Where the add or insert took place.

Here is a concrete tour through the _add_ event handler's arguments:

```
var list = new can.List();
list.bind('add', function(ev, newElements, index) {
    console.log(newElements + ', ' + index);
});

list.attr(['Alexis', 'Bill']); // ['Alexis', 'Bill'], 0
list.attr(2, 'Eve');           // Eve, 2
list.attr(0, 'Adam');
list.attr(['Alice', 'Bob']);

list.removeAttr(1);
```

### The _remove_ event

_remove_ events are fired when elements are removed from the list.

The parameters of the event handler for the _remove_ event are:

- _ev_ The event object.
- _removedElements_ The removed elements.
 If more than one element was removed, _removedElements_ will be an array. Otherwise, it is simply the element itself.
- _index_ Where the removal took place.

Here is a concrete tour through the _remove_ event handler's arguments:

```
var list = new can.List();
list.bind('remove', function(ev, removedElements, index) {
    console.log(removedElements + ', ' + index);
});

list.attr(['Alexis', 'Bill']);
list.attr(2, 'Eve');
list.attr(0, 'Adam');
list.attr(['Alice', 'Bob']);

list.removeAttr(1);            // Bob, 1
```

### The _length_ event

_length_ events are fired whenever the list changes.

The parameters of the event handler for the _length_ event are:

- _ev_ The event object.
- _length_ The current length of the list.
 If events were batched when the _length_ event was triggered, _length_ will have the length of the list when `stopBatch` was called. Because of this, you may receive multiple _length_ events with the same _length_ parameter.

Here is a concrete tour through the _length_ event handler's arguments:

```
var list = new can.List();
list.bind('length', function(ev, length) {
    console.log(length);
});

list.attr(['Alexis', 'Bill']); // 2
list.attr(2, 'Eve');           // 3
list.attr(0, 'Adam');
list.attr(['Alice', 'Bob']);

list.removeAttr(1);            // 2
```
