@constructor can.List
@inherits can.Map
@download can/list
@test can/list/test.html
@parent canjs
@release 2.0

@group can.List.prototype 0 Prototype
@group can.List.static 1 Static
@group can.List.plugins 2 plugins

@link ../docco/list/list.html docco

Use for observable array-like objects.

@signature `new can.List([array])`

Create an observable array-like object.

@param {Array} [array] Items to seed the List with.

@return {can.List} An instance of `can.List` with the elements from _array_.

@signature `new can.List(deferred)`

@param {can.Deferred} deferred A deferred that resolves to an 
array.  When the deferred resolves, its values will be added to the list.

@return {can.List} An initially empty `can.List`.  


@body

## Use

`can.List` is used to observe changes to an Array.  `can.List` extends `[can.Map]`, so all the 
ways that you're used to working with Maps also work here.

Use [can.List::attr attr] to read and write properties of a list:

    var hobbies = new can.List(["JS","Party Rocking"])
    hobbies.attr(0)        //-> "JS"
    hobbies.attr("length") //-> 2
    
    hobbies.attr(0,"JavaScript")
    
    hobbies.attr()         //-> ["JavaScript","Party Rocking"]

Just as you shouldn't set properties of an Map directly, you shouldn't change elements
of a List directly. Always use `attr` to set the elements of a List, or use [can.List::push push],
[can.List::pop pop], [can.List::shift shift], [can.List::unshift unshift], or [can.List::splice splice].

Here is a tour through the forms of `can.List`'s `attr` that parallels the one found under [can.Map.prototype.attr attr]:

```
var people = new can.List(['Alex', 'Bill']);

// set an element:
people.attr(0, 'Adam');
people[0] = 'Adam'; // don't do this!

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
```

## Listening to changes

As with `can.Map`s, the real power of observable arrays comes from being able to
react to changes in the member elements of the array. Lists emit five types of events:

- the _change_ event fires on every change to a List.
- the _set_ event is fired when an element is set.
- the _add_ event is fired when an element is added to the List.
- the _remove_ event is fired when an element is removed from the List.
- the _length_ event is fired when the length of the List changes.

This example presents a brief concrete survey of the times these events are fired:

```
var list = new can.List(['Alice', 'Bob', 'Eve']);

list.bind('change', function() { console.log('An element changed.'); });
list.bind('set', function() { console.log('An element was set.'); });
list.bind('add', function() { console.log('An element was added.'); });
list.bind('remove', function() { 
  console.log('An element was removed.'); 
});
list.bind('length', function() { 
  console.log('The length of the list changed.'); 
});

list.attr(0, 'Alexis'); // 'An element changed.'
                        // 'An element was set.'

list.attr(3, 'Xerxes'); // 'An element changed.'
                        // 'An element was added.'
                        // 'The length of the list was changed.'

list.attr(['Adam', 'Bill']); // 'An element changed.'
                             // 'An element was set.'
                             // 'An element was changed.'
                             // 'An element was set.'

list.pop(); // 'An element changed.'
            // 'An element was removed.'
            // 'The length of the list was changed.'
```

More information about binding to these events can be found under [can.List::attr attr].
