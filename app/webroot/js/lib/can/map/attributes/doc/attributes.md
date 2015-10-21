@page can.Map.attributes attributes
@parent can.Map.plugins
@plugin can/map/attributes
@test can/map/attributes/test.html
@download http://donejs.com/can/dist/can.map.attributes.js
@group can.Map.attributes.static static
@group can.Map.attributes.prototype prototype


@deprecated {2.1} The attributes plugin (and the setter plugin) have been deprecated in 
favor of the new [can.Map.prototype.define define] plugin, which provides the same 
functionality. It will still be maintained up to 3.0 and potentially after. 
Projects using converters or serializers should consider switching to [can.Map.prototype.define.type define's type] 
and [can.Map.prototype.define.serialize define's serialize].

@body

## Use

can.Map.attributes is a plugin that helps convert and normalize data being set on an Map
and allows you to specify the way complex types get serialized. The attributes plugin is most
helpful when used with [can.Model] \(because the serialization aids in sending data to a server),
but you can use it with any Map you plan to make instances
from.

There are three important static properties to give the class you want to use attributes with:

- `[can.Map.attributes.static.attributes attributes]` lists the properties that will be normalized
and the types those properties should be.
- `[can.Map.attributes.static.convert convert]` lists how to convert and normalize arbitrary values
to the types this class uses.
- `[can.Map.attributes.static.serialize serialize]` lists serialization algorithms for the types
this class uses.

Together, the functions in _convert_ and _serialize_ make up the type definitions for the class.
The attributes plugin comes with three useful predefined types: `'date'`, `'number'`, and `'boolean'`.

Here is a quick example of an Map-based class using the attributes plugin to convert and normalize
its data, and then to serialize the instance:


    Bio = can.Map.extend({
        attributes: {
        birthday: 'date',
        weight: 'number'
    }
    // Bio only uses built-in types, so no
    // need to specify serialize or convert.
    }, {});

    var alice = new Bio({
        birthday: Date.parse('1985-04-01'), // 481161600000
        weight: '120'
    });

    alice.attr();      // { birthday: Date(481161600000), weight: 120 }
    alice.serialize(); // { birthday: 481161600000, weight: 120 }


### Demo

When a user enters a new date in the format of _YYYY-MM-DD_, the control 
listens for changes in the input box and updates the Map using 
the `attr` method which then converts the string into a JavaScript date object.  

Additionally, the control also listens for changes on the Map and 
updates the age in the page for the new birthdate of the contact.

@demo can/map/attributes/doc/attributes.html

### Reference types

Types listed in `attributes` can also be a functions, such as the `model` or
`models` methods of a [can.Model]. When data of this kind of type is set, this
function is used to convert the raw data into an instance of the Model.

This example builds on the previous one to demonstrate these reference types.

    Bio = can.Map.extend({
        attributes: {
        birthday: 'date',
        weight: 'number'
    }
    // Contact only uses built-in types, so you don't have
    // to specify serialize or convert.
    }, {});

    Contact = can.Map.extend({
        attributes: {
            bio: 'Bio.newInstance'
        }
    }, {});

    var alice = new Contact({
        first: 'Alice',
        last: 'Liddell',
        bio: {
            birthday: Date.parse('1985-04-01'), // 481161600000
            weight: 120
        }
    });

The Attributes plugin provides functionality for converting data attributes from raw types and 
serializing complex types for the server.

Below is an example code of an Map providing serialization and conversion for dates and numbers.  

When `Contact` is initialized, the `weight` attribute is set and converted to a `number` using the
converter we provided.  Next the `birthday` attribute is set using the `attr` method and gets converted
as well.  Lastly, `serialize` is invoked converting the new attributes to raw types for the server.


	var Contact = can.Map.extend({
		attributes: {
			birthday: 'date',
			weight: 'number'
		},
		serialize : {
			date : function( val, type ){
				// returns the string formatted as 'YYYY-DD-MM'
				return val.getYear() + 
						"-" + (val.getMonth() + 1) + 
						"-" + val.getDate(); 
			},
			number: function(val){
				return val + '';
			}
		},
		convert: {
			// converts string to date
			date: function( date ) {
				if ( typeof date == 'string' ) {
					//- Extracts dates formated 'YYYY-DD-MM'
					var matches = raw.match( /(\d+)-(\d+)-(\d+)/ ); 
					
					//- Parses to date object and returns
					date = new Date( matches[ 1 ],
							( +matches[ 2 ] ) - 1, 
							matches[ 3 ] ); 
				}
				
				return date;
			},
		
			// converts string to number
			number: function(number){
				if(typeof number === 'string'){
					number = parseInt(number);
				}
				return number;
			}
		}
	}, {});

	var brian = new Contact({
		weight: '300'
	});
	
	var weight = brian.attr('weight'); //-> 300

	//- sets brian's birthday
	brian.attr('birthday', '11-29-1983');

	var date = brian.attr('birthday'); //-> Date()

	var seralizedObj = brian.serialize();
	//-> { 'birthday': '11-29-1983', 'weight': '300' }
	

## Converter functions

Another common case is to create converter functions (`function(value, oldValue) {}`) that return a converted value:

	var ValueMap = can.Map.extend({
		attributes: {
			value: function(orig) {
				return orig * 100;
			}
		}
	},{});

	console.log(new ValueMap({ value: 0.83 }).attr('value'));


## Associations

The attribute plugin also allows setting up data associations between Maps or Models. This means
that nested data structures can be automatically converted into their Map or Model (using `Model.models`) representations by passing them as the attribute.
If the value to convert is an array it will be converted into its `can.Map.List` or `can.Model.List` (using `can.Model.models`) representation:

	var Sword = can.Model.extend({
		findAll: 'GET /swords'
	}, {
		getPower: function() {
			return this.attr('power') * 100;
		}
	});

	var Level = can.Model.extend({
		findAll: 'GET /levels'
	}, {
		getName: function() {
            return 'Level: ' + this.attr('name');
        }
	});

	var Zelda = can.Model.extend({
		findOne: 'GET /zelda/{id}'
		attributes: {
			sword: Sword,
			levelsCompleted: Level
		}
	},{});


Assuming that `Zelda.findOne({ id: 'link' })` will return something like:

	{
        sword: {
            name: 'Wooden Sword',
            power: 0.2
        },
        levelsCompleted : [
            {id: 1, name: 'Aquamentus'},
            {id: 2, name: 'Dodongo'}
        ]
    }

The converted data will contain a list or Levels and a sword Model:

	Zelda.findOne({ id: 'link' }).then(function(link) {
		console.log(link.attr('sword').getPower()); // -> 20
		console.log(link.attr('levelsCompleted')[0].getName());
		// -> 'Level: Aquamentus'
	});

### Demo

Below is a demo that showcases associations between 2 different models to show the tasks
for each contact and how much time they have left to complete the task(s) using converters.

@demo can/map/attributes/doc/attributes-assocations.html
