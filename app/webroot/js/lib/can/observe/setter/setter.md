@page can.Observe.setter
@parent can.Observe
@plugin can/observe/setter
@download http://donejs.com/can/dist/can.observe.setter.js
@test can/observe/setter/qunit.html

`can.Observe.setter(name, success(value), error(errors))` extends the Observe object 
to provide convenient helper methods for setting attributes on a observable.

The `attr` function looks for a `setATTRNAME` function to handle setting 
the `ATTRNAME` property.

By providing a function that takes the raw data and returns a form useful for JavaScript, 
we can make our observes automatically convert data.

	var Contact = can.Observe({
		setBirthday : function(raw){
			if(typeof raw == 'number'){
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

## Error Handling

Setters can trigger errors if values passed didn't meet your defined validation(s).

Below is an example of a _School_ observable that accepts a name property and errors
when no value or a empty string is passed.

	var School = can.Observe({
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

@demo can/observe/setter/setter-paginate.html