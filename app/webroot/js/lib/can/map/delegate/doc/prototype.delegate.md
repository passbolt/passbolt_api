@function can.Map.prototype.delegate delegate
@parent can.Map.delegate 0
@plugin can/map/delegate
@signature `observe.delegate( selector, event, handler )`

@body
`delegate( selector, event, handler(ev,newVal,oldVal,from) )` listen for changes
in a child attribute from the parent. The child attribute
does not have to exist.

```
// create an observable
var o = new can.Map({
    foo : {
        bar : 'Hello World'
    }
});

//listen to changes on a property
o.delegate('foo.bar', 'change', function(ev, prop, how, newVal, oldVal) {
    // foo.bar has been added, set, or removed
    this //->
});

// change the property
o.attr('foo.bar', 'Goodbye Cruel World');
```

## Types of events

Delegate lets you listen to add, set, remove, and change events on property.

### add

An add event is fired when a new property has been added.

```
var o = new can.Control({});

o.delegate('name', 'add', function(ev, value) {
    // called once
    can.$('#name').show()
});

o.attr('name', 'Justin')
o.attr('name', 'Brian');
```

Listening to add events is useful for 'setup' functionality (in this case
showing the <code>#name</code> element.

### set

Set events are fired when a property takes on a new value.  set events are
always fired after an add.

```
o.delegate('name', 'set', function(ev, value) {
    // called twice
    can.$('#name').text(value)
});

o.attr('name', 'Justin')
o.attr('name', 'Brian');
```

### remove

Remove events are fired after a property is removed.

```
o.delegate('name', 'remove', function(ev) {
    // called once
    $('#name').text(value)
});

o.attr('name', 'Justin');
o.removeAttr('name');
```

## Wildcards - matching multiple properties

Sometimes, you want to know when any property within some part
of an observe has changed. Delegate lets you use wildcards to
match any property name.  The following listens for any change
on an attribute of the params attribute:

```
var o = can.Control({
    options : {
        limit : 100,
        offset: 0,
        params : {
            parentId: 5
        }
    }
});

o.delegate('options.*', 'change', function() {
    alert('1');
});
o.delegate('options.**', 'change', function() {
    alert('2');
});

// alerts 1
// alerts 2
o.attr('options.offset', 100);

// alerts 2
o.attr('options.params.parentId', 6);
```

Using a single wildcard (`*`) matches single level
properties.  Using a double wildcard (`**`) matches
any deep property.

## Listening on multiple properties and values

Delegate lets you listen on multiple values at once.  The following listens
for first and last name changes:

```
var o = new can.Map({
    name : { 
      first: 'Justin', 
      last: 'Meyer'
    }
});

o.bind('name.first, name.last',
    'set',
    function(ev, newVal, oldVal, from) {

});
```

## Listening when properties are a particular value

Delegate lets you listen when a property is __set__ to a specific value:

```
var o = new can.Map({
    name : 'Justin'
});

o.bind('name=Brian',
    'set',
    function(ev, newVal, oldVal, from) {

});
```

@param {String} selector The attributes you want to listen for changes in.

Selector should be the property or
property names of the element you are searching.  Examples:

- "name" - listens to the "name" property changing
- "name, address" - listens to "name" or "address" changing
- "name address" - listens to "name" or "address" changing
- "address.*" - listens to property directly in address
- "address.**" - listens to any property change in address
- "foo=bar" - listens when foo is "bar"

@param {String} event The event name.  One of ("set","add","remove","change")
@param {Function} handler(ev,newVal,oldVal,prop) The callback handler
called with:

- newVal - the new value set on the observe
- oldVal - the old value set on the observe
- prop - the prop name that was changed

@return {can.Map} the observe for chaining