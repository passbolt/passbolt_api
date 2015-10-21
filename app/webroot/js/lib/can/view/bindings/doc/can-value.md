@function can.view.bindings.can-value can-value
@parent can.view.bindings

Sets up two way bindings in a template.

@signature `can-value='KEY'`

Binds the element's value or checked property to the value specified by
key. Example:

    <input type='text' can-value='first.name'/>

@param {can.mustache.key} key A named value in the current scope.

@body

## Use

Add a `can-value="KEY"` attribute to an input or select element and
the element's value will be cross-bound to an observable value specified by `KEY`.

Depending on the element and the element's type, `can-value` takes on
different behaviors.  If an input element has a type
not listed here, the behavior is the same as the `text` type.

## input type=text

Cross binds the input's string text value with the observable value.

The value of the observable is changed after the input's `change` event, 
which is after `blur`.

@demo can/view/bindings/doc/hyperloop.html

## input type=checkbox

Cross binds the checked property to a true or false value. An alternative
true and false value can be specified by setting `can-true-value` and
`can-false-value` attributes.

@demo can/view/bindings/doc/input-checkbox.html

## input type='radio'

If the radio element is checked, sets the observable specified by `can-value` to match the value of
`value` attribute.

@demo can/view/bindings/doc/input-radio.html

## select

Cross binds the selected option value with an observable value.

@demo can/view/bindings/doc/select.html