@function can.Map.validations.static.validatesNumericalityOf validatesNumericalityOf
@parent can.Map.validations 7

@signature `observe.validatesNumericalityOf(attrNames)`

Validates that the specified attributes is a valid Number.

	init : function(){
		this.validatesNumericalityOf(["age"]);
	}

@param {Array|String} attrNames Attribute name(s) to to validate