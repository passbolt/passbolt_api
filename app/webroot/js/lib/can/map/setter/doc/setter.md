@function can.Map.setter setter
@parent can.Map.plugins
@plugin can/map/setter
@test can/map/setter/test.html

Specify setter methods on [can.Map can.Maps].

@deprecated {2.1} The setter plugin (and the attributes plugin) have been deprecated in 
favor of the new [can.Map.prototype.define define] plugin, which provides the same 
functionality. It will still be maintained up to 3.0 and potentially after. 
Projects using setters should consider switching to [can.Map.prototype.define.set define setters].

@signature `setATTR: function(newValue,setValue,setErrors)`

Specifies a setter method for the `ATTR` attribute.

@param {String} ATTR The capitalized attribute name this setter will set. 

@param {*} newValue The propsed value of the attribute specified by [can.Map::attr].

@param {can.Map.setter.setValue} setValue A callback function that can specify `undefined` values
or the value at a later time.

@param {can.Map.setter.setErrors} setErrors A callback function that can specify error data if
the proposed value is in error.

@return {*} If a non-undefined value is returned, that value is set as the attribute's value. If
undefined is returned, it's assumed that the `setValue` callback will be called.  Use `setValue` to
set undefined values.

@body

## Use

`can.Map.setter(name, setValue(value), setErrors(errors))` extends the Map object 
to provide convenient helper methods for setting attributes on a map.

The [can.Map::attr attr] function looks for a camel-case `setATTR` function to handle setting 
the `ATTR` property. For example, the following makes sure the `birthday` attribute is 
always a Date type.

	var Contact = can.Map.extend({
		setBirthday : function(raw){
			if(typeof raw === 'number'){
				return new Date( raw )
			}else if(raw instanceof Date){
				return raw;
			}
		}
	});
	
	var contact = new Contact({ birthday: 1332777411799 });
	contact.attr('birthday') //-> Date(Mon Mar 26 2012)

By providing a function that takes the raw data and returns a form useful for JavaScript, 
we can make our maps automatically convert data.

	var Contact = can.Map.extend({
		setBirthday : function(raw){
			if(typeof raw === 'number'){
				return new Date( raw )
			}else if(raw instanceof Date){
				return raw;
			}
		}
	});

	// set on init
	var contact = new Contact({ birthday: 1332777411799 });
	
	// get the contact's birthday via 'attr' method
	contact.attr('birthday') 
		// -> Mon Mar 26 2012 08:56:51 GMT-0700 (MST)

	// set via 'attr' method
	contact.attr('birthday', new Date('11/11/11').getTime())
	
	contact.attr('birthday') 
		// -> Fri Nov 11 2011 00:00:00 GMT-0700 (MST)

	contact.attr({
		'birthday': new Date('03/31/12').getTime()
	});

	contact.attr('birthday') 
		// -> Sat Mar 31 2012 00:00:00 GMT-0700 (MST)


If the returned value is `undefined`, this means the setter is either in an async 
event or the attribute(s) were not set. 

## Differences From `attr`

The way that return values from setters affect the value of an Map's property is
different from [can.Map::attr attr]'s normal behavior. Specifically, when the 
property's current value is an Map or List, and an Map or List is returned
from a setter, the effect will not be to merge the values into the current value as
if the return value was fed straight into `attr`, but to replace the value with the
new Map or List completely:

```
var Contact = can.Map.extend({
	setInfo: function(raw) {
      return raw;
	}
});

var alice = new Contact({info: {name: 'Alice Liddell', email: 'alice@liddell.com'}});
alice.attr(); // {name: 'Alice Liddell', 'email': 'alice@liddell.com'}
alice.info._cid; // '.map1'

alice.attr('info', {name: 'Allison Wonderland', phone: '888-888-8888'});
alice.attr(); // {name: 'Allison Wonderland', 'phone': '888-888-8888'}
alice.info._cid; // '.map2'
```

If you would rather have the new Map or List merged into the current value, call
`attr` inside the setter:

```
var Contact = can.Map.extend({
	setInfo: function(raw) {
      this.info.attr(raw);
      return this.info;
	}
});

var alice = new Contact({info: {name: 'Alice Liddell', email: 'alice@liddell.com'}});
alice.attr(); // {name: 'Alice Liddell', 'email': 'alice@liddell.com'}
alice.info._cid; // '.Map1'

alice.attr('info', {name: 'Allison Wonderland', phone: '888-888-8888'});
alice.attr(); // {name: 'Allison Wonderland', email: 'alice@liddell.com', 'phone': '888-888-8888'}
alice.info._cid; // '.Map1'
```

## Error Handling

Setters can trigger errors if values passed didn't meet your defined validation(s).

Below is an example of a _School_ observable that accepts a name property and errors
when no value or a empty string is passed.


	var School = can.Map.extend({
		setName : function(name, success, error){
			if(!name){
				error("no name");
			}
			return error;
		}
	});

	var school = new School();
	
	// bind to error handler
	school.bind("error", function(ev, attr, error){
		alert("no name")
	})
	
	// set to empty string
	school.attr("name","");

## Demo

The example app is a pagination widget that updates
the offsets when the _Prev_ or _Next_ button is clicked.

@demo can/map/setter/setter-paginate.html

Notice the `setCount` and `setOffset` setters.
