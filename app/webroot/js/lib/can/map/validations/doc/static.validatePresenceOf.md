@function can.Map.validations.static.validatePresenceOf validatePresenceOf
@parent can.Map.validations 5

@signature `observe.validatePresenceOf(attrNames, options)`

Validates that the specified attributes are not blank.

	init : function(){
		this.validatePresenceOf(["name"])
	}

@param {Array<String>|String} attrNames Attribute name(s) to to validate
@param {Object} [options] Options for the validations.  Valid options include 'message' and 'testIf'.