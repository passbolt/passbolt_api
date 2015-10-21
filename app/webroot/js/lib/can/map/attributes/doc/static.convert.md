@property can.Map.attributes.static.convert convert
@parent can.Map.attributes.static 1

You often want to convert from what the observe sends you to a form more useful to JavaScript.
For example, contacts might be returned from the server with dates that look like: "1982-10-20".
We can observe to convert it to something closer to `new Date(1982,10,20)`.

Convert comes with the following types:

- __date__ Converts to a JS date. Accepts integers or strings that work with Date.parse
- __number__ An integer or number that can be passed to parseFloat
- __boolean__ Converts "false" to false, and puts everything else through Boolean()

The following sets the birthday attribute to "date" and provides a date conversion function:

	var Contact = can.Map.extend({
		attributes : {
			birthday : 'date'
		},
		convert : {
			date : function(raw){
				if(typeof raw == 'string'){
					//- Extracts dates formated 'YYYY-DD-MM'
					var matches = raw.match(/(\d+)-(\d+)-(\d+)/);

					//- Parses to date object and returns
					return new Date(matches[1],
							        (+matches[2])-1,
								    matches[3]);

				}else if(raw instanceof Date){
					return raw;
				}
			}
		}
	},{});

	var contact = new Contact();

	//- calls convert on attribute set
	contact.attr('birthday', '4-26-2012')

	contact.attr('birthday'); //-> Date

If a property is set with an object as a value, the corresponding converter is called with the unmerged data (the raw object)
as the first argument, and the old value (a can.Map) as the second:

	var MyObserve = can.Map.extend({
		attributes: {
	nested: "nested"
		},
		convert: {
			nested: function(data, oldVal) {
				if(oldVal instanceof MyObserve) {
					return oldVal.attr(data);
				}
				return new MyObserve(data);
			}
		}
	},{});

## Differences From `attr`

The way that return values from convertors affect the value of an Observe's property is
different from [can.Map::attr attr]'s normal behavior. Specifically, when the
property's current value is an Observe or List, and an Observe or List is returned
from a convertor, the effect will not be to merge the values into the current value as
if the return value was fed straight into `attr`, but to replace the value with the
new Observe or List completely. Because of this, any bindings you have on the previous
observable object will break.

If you would rather have the new Observe or List merged into the current value, call
`attr` directly on the property instead of on the Observe:

```
var Contact = can.Map.extend({
attributes: {
 info: 'info'
},
convert: {
 'info': function(data, oldVal) {
   return data;
}
}
}, {});

var alice = new Contact({info: {name: 'Alice Liddell', email: 'alice@liddell.com'}});
alice.attr(); // {name: 'Alice Liddell', 'email': 'alice@liddell.com'}
alice.info._cid; // '.observe1'

alice.attr('info', {name: 'Allison Wonderland', phone: '888-888-8888'});
alice.attr(); // {name: 'Allison Wonderland', 'phone': '888-888-8888'}
alice.info._cid; // '.observe2'

alice.info.attr({email: 'alice@wonderland.com', phone: '000-000-0000'});
alice.attr(); // {name: 'Allison Wonderland', email: 'alice@wonderland.com', 'phone': '000-000-0000'}
alice.info._cid; // '.observe2'
```

## Assocations and Convert

If you have assocations defined within your model(s), you can use convert to automatically
call serialize on those models.

```
var Contact = can.Model.extend({
attributes : {
 tasks: Task
}
}, {});

var Task = can.Model.extend({
attributes : {
 due : 'date'
}
},{});

var contact = new Contact({
tasks: [ new Task({
 due: new Date()
}) ]
});

contact.serialize();
//-> { tasks: [ { due: 1333219754627 } ] }
```