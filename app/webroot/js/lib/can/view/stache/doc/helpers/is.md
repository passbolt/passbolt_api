@function can.stache.helpers.is {{#is expr1 expr2}}
@parent can.stache.htags 12

@signature `{{#is expr...}}BLOCK{{/is}}`

Renders the `BLOCK` template within the current template.

@param {can.stache.expression} [expr...] An expression or key that references a value within the current or parent

@param {can.stache} BLOCK A template that is rendered
if the result of comparsion `expr1` and `expr2` value is truthy.

@return {DocumentFragment} If the key's value is truthy, the `BLOCK` is rendered with the
current context and its value is returned; otherwise, an empty string.

@body

The `is` helper compares expr1 and expr2 and renders the blocks accordingly.

	{{#is expr1 expr2}}
		// truthy
	{{else}}
		// falsey
	{{/is}}
