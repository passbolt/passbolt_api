@function can.view.bindings.can-value-radio input type=radio
@parent can.view.bindings.can-value

Cross bind a value to a radio input.

@signature `<input type='radio' can-value='KEY' value='VALUE'/>`

If the radio element is checked, sets the observable specified by `can-value` to match the value of 
`value` attribute.  

@param {can.mustache.key} KEY A named value in the current scope. It should reference a
a [can.Map] property or a [can.compute].

@param {String} VALUE Used to set the value of `KEY` when the radio input is checked.

@body

## Use

@demo can/view/bindings/doc/input-radio.html
