@function can.view.bindings.can-value-select select
@parent can.view.bindings.can-value

Cross bind a value to a `<select>` element.

@signature `<select can-value='KEY'/>`

Cross binds the selected option value with an observable value.

@param {can.mustache.key} KEY A named value in the current 
scope. `KEY`'s value is cross bound with the selected `<option>` in
the `<select>`. `KEY` should specify either a [can.Map] property or
a [can.compute].

@body

## Use

The following cross bind's a `<select>` to a `person` map's `attending` property:

@demo can/view/bindings/doc/select.html