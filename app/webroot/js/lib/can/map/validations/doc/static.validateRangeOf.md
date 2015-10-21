@function can.Map.validations.static.validateRangeOf validateRangeOf
@parent can.Map.validations 6 

@signature `observe.validateRangeOf(attrNames, low, hi, options)`

Validates that the specified attributes are in the given numeric range.

	init : function(){
		this.validateRangeOf(["age"],21, 130);
	}

@param {Array<String>|String} attrNames Attribute name(s) to to validate
@param {Number} low Minimum value (inclusive)
@param {Number} hi Maximum value (inclusive)
@param {Object} [options] (optional) Options for the validations.  Valid options include 'message' and 'testIf'.