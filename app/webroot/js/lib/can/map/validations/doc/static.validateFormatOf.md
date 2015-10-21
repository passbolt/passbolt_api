@function can.Map.validations.static.validateFormatOf validateFormatOf
@parent can.Map.validations 2

@signature `observe.validateFormatOf(attrNames, regexp, options)`

@param {Array<String>|String} attrNames Attribute name(s) to to validate
@param {RegExp} regexp Regular expression used to match for validation
@param {Object} [options] Options for the validations.  Valid options include 'message' and 'testIf'.

@body

`validateFormatOf(attrNames, regexp, options)` validates where the values of
specified attributes are of the correct form by
matching it against the regular expression provided.

	init : function(){
		this.validateFormatOf(["email"],/[\w\.]+@]w+\.\w+/,{
			message : "invalid email"
		})
	}