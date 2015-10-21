@function can.Map.validations.static.validate validate
@parent can.Map.validations 1

@body
The following example validates that a person's age is a number:

	Person = can.Map.extend({
		 init : function(){
			 this.validate(["age"], function(val){
				 if( typeof val === 'number' ){
					 return "must be a number"
				 }
			 })
		 }
	},{})


The error message can be overwritten with `options` __message__ property:

	Person = can.Map.extend({
		 init : function(){
			 this.validate(
				 "age",
			 {message: "must be a number"},
			 function(val){
					 if( typeof val === 'number' ){
						 return true
					 }
			 })
	 }
	},{})

@signature `observe.validate(attrNames, [options,] validateProc)`

@param {Array<String>|String} attrNames Attribute name(s) to to validate

@param {Object} [options] Options for the
validations.  Valid options include 'message' and 'testIf'.

@param {function(*,String)} validateProc(value,attrName) Function used to validate each
given attribute. Returns nothing if valid and an error message
otherwise. Function is called in the instance context and takes the
`value` and `attrName` to validate.

`validate(attrNames, [options,] validateProc(value, attrName) )` validates each of the
specified attributes with the given `validateProc` function.  The function
should return a value if there is an error.  By default, the return value is
the error message.  Validations should be set in the Constructor's static init method.