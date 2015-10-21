@function can.view.bindings.can-value-checkbox input type=checkbox
@parent can.view.bindings.can-value

Cross bind a value to a checkbox.

@signature `<input type='checkbox' can-value='KEY' can-true-value='TRUEVALUE' can-false-value='FALSEVALUE'/>`

Cross binds the checked property to a true or false value.

@param {can.mustache.key} KEY A named value in the current scope.

@param {String} [TRUEVALUE] Used to set the checked value of `KEY`.

@param {String} [FALSEVALUE] Used to set the unchecked value of `KEY`.

@body

## Use

@demo can/view/bindings/doc/input-checkbox.html

## Using can-true-value

An alternative true and false value can be specified by setting `can-true-value` and
`can-false-value` attributes.  This is used for setting up a "boolean" property that only has two possible valid values, 
whose values are modelled by the true/false checked property of a checkbox, as in the following example:

	<input type='checkbox' can-value='sex' can-true-value='male' can-false-value='female'/>

In this case, the data passed in contains a 'sex' property which is either 'male' or 'female'. Specifying the string values for true and false in the attributes forces the data to two way bind using these string properties.

@demo can/view/bindings/doc/input-checkbox-trueval.html