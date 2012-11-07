@class can.Construct
@parent canjs


can.Construct provides easy prototypal inheritance for JavaScript by creating constructor
functions that can be used with the __new__ keyword. It  is based off John Resig's
[http://ejohn.org/blog/simple-javascript-inheritance/|Simple JavaScript Inheritance] library.

## Creating Constructor Functions

To create a constructor function,  call `can.Construct( [ NAME, staticProperties, ] instanceProperties )`.

	var Animal = can.Construct({
		breathe : function () {
			console.log('Breathing');
		}
	});

`Animal` is a constructor function and instances of Animal have a `breathe()` method. We 
can create a `new Animal` object and call `breathe()` on it like:

    var man = new Animal();
    man.breathe();
    man instanceof Animal //-> true

If you want to create a sub-class (a constructor function that inherits properties from a base constructor function),
call the the  base constructor function with the new constructor function's properties:

    Dog = Animal({
    	bark : function () {
    		console.log('Woof!');
    	}
    });

    var dog = new Dog;
    dog.bark();
    dog.breathe();

## Instantiation

When a new class instance is created, it calls the class's `init` method with the arguments passed
to the constructor function:

	var Person = can.Construct({
		init : function (name) {
			this.name = name;
		},
		speak : function () {
			return "I am " + this.name + ".";
		}
	});
    
    var payal = new Person("Payal");
    console.log(payal.speak());
    // -> I am Payal.

## Static Inheritance 

If you pass two objects to can.Construct, the first one will be attached directly to the constructor function.
This is pretty much the same as static properties in most class based languages.
You can access static properties directly on the construct object or in a prototype method by accessing the
[can.Construct::constructor] using `this.constructor`. The following example creates a Person construct
that increments a counter for each instance created:

	var Person = can.Construct({
		count : 0
	}, {
		init : function(name) {
			this.name = name;
			this.constructor.count++;
		}
	});

	var justin = new Person('Justin');
	console.log(Person.count); // -> 1

## Introspection

Constructor functions are anonymous, meaning that they don't carry any naming or namespace information.
You can however pass a namespace string when defining a can.Construct which will make the constructor
function available globally in that namespace and also set the
[can.Construct.static.shortName], [can.Construct.static.fullName] and [can.Construct.static.namespace]
static properties.

    can.Construct("Bitovi.Person", {
        init : function(name) {
            this.name = name;
        }
    });

    console.log(Bitovi.Person.shortName); // -> 'Person'
    console.log(Bitovi.Person.fullName);  //-> 'Bitovi.Person'
    console.log(Bitovi.Person.namespace); //-> [Object]
    
    var person = new Bitovi.Person();
    console.log(person.constructor.shortName); // -> 'Person'

## Plugins

can.Construct can be used with these two plugins:

[can.Construct.super]: Adds access to the prototype by adding `this._super` to overwritten methods

[can.Construct.proxy]: Is a flexible way to create callbacks from constructor functions
