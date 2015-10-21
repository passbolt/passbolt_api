@function can.Map.validations.static.validateInclusionOf validateInclusionOf
@parent can.Map.validations 3

@signature `observe.validateInclusionOf(attrNames, inArray, options)`

Validates whether the values of the specified attributes are available in a particular
array.

	init : function(){
		this.validateInclusionOf(["salutation"],["Mr.","Mrs.","Dr."])
	}

@param {Array<String>|String} attrNames Attribute name(s) to to validate
@param {Array} inArray Array of options to test for inclusion
@param {Object} [options] Options for the validations.  Valid options include 'message' and 'testIf'.