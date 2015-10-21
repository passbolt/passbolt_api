@function can.Map.validations.static.validateLengthOf validateLengthOf
@parent can.Map.validations 4

@signature `observe.validateLengthOf(attrNames, min, max, options)`

Validates that the specified attributes' lengths are in the given range.

	init : function(){
		this.validateLengthOf(["suffix"],3,5)
	}

@param {Array<String>|String} attrNames Attribute name(s) to to validate
@param {Number} min Minimum length (inclusive)
@param {Number} max Maximum length (inclusive)
@param {Object} [options] Options for the validations.  Valid options include 'message' and 'testIf'.
