@page can.Construct.super
@parent can.Construct.plugins
@plugin can/construct/super
@test can/construct/super/test.html
@download http://donejs.com/can/dist/can.construct.super.js

can.Construct.super is a plugin that makes it easier to call base
functions from inside inheriting functions.

@signature `construct._super([...args])`

Calls the base constructor function's method.

@param {...[*]} args parameters to pass to the base function

@body
With this plugin, functions that are inheriting from base functions
are provided with a specialized `this._super` reference to the base
function from which they inherit.

This is especially useful for calling base classes' `[can.Construct::init init]` and `[can.Construct::setup setup]`, but it can be used in any inheriting function.

The `Person` and `Programmer` examples from `[can.Construct::init init]` demonstrate `_super`'s use.
Here's how those classes look without can.Construct.super:

```
var Person = can.Construct.extend({
    init: function(first, last) {
        this.first = first;
        this.last  = last;
    }
});

var Programmer = Person.extend({
    init: function(first, last, language) {
        // call base's init
        Person.prototype.init.apply(this, arguments);

        // other initialization code
        this.language = language;
    },
    bio: function() {
        return "Hi! I'm " + this.first + " " + this.last +
            " and I write " + this.language + ".";
    }
});
```

And here's how `Programmer` works using `_super`:

```
var Programmer = Person.extend({
    init: function(first, last, language) {
        // call base's init
        this._super(first, last);

        // other initialization code
        this.language = language;
    },
    bio: function() {
        return "Hi! I'm " + this.first + " " + this.last +
            " and I write " + this.language + ".";
    }
});
```

If you want to pass an array of arguments (or an arguments object) to `_super`, use [apply](https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Function/apply):

```
var Programmer = Person.extend({
    init: function(first, last, language) {
        // call base's init
        this._super.apply(this, arguments);

        // other initialization code
        this.language = language;
    },
    bio: function() {
        return "Hi! I'm " + this.first + " " + this.last +
            " and I write " + this.language + ".";
    }
});
```

## `_super` on constructors

can.Construct.super also adds `super` to the constructor, so you
can use it in static functions.

Here is a base class that has a method that squares numbers and an inherited class that has a method that cubes numbers:

```
var Squarer = can.Construct.extend({
    raise: function(n) {
        return n*n;
    }
}, {});

var Cuber = Squarer.extend({
    raise: function(n) {
        return n * this._super(n);
    }
}, {});
```
