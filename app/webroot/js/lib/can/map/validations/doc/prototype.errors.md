@function can.Map.validations.prototype.errors errors
@parent can.Map.validations 0
@signature `observe.errors(attrs, newVal)`
@param {Array<String>|String} [attrs] An optional list of attributes to get errors for:

	task.errors(['dueDate','name']);

Or it can take a single attr name like:

	task.errors('dueDate')

@param {Object} [newVal] An optional new value to test setting
on the observe.  If `newVal` is provided,
it returns the errors on the observe if `newVal` was set.

@return {Object<String, Array<String>>} an object of attributeName : [errors] like:

 	task.errors() // -> {dueDate: ["can't be empty"]}

or `null` if there are no errors.

@body


Runs the validations on this observe.  You can
also pass it an array of attributes to run only those attributes.
It returns nothing if there are no errors, or an object
of errors by attribute.

To use validations, it's suggested you use the
observe/validations plugin.

	Task = can.Map.extend({
		init : function(){
			this.validatePresenceOf("dueDate")
		}
	},{});

	var task = new Task(),
		 errors = task.errors()

	errors.dueDate[0] //-> "can't be empty"