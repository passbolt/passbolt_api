@function can.stache.helpers.else {{else}}
@parent can.stache.htags 3

@signature `{{#helper}}BLOCK{{else}}INVERSE{{/helper}}`

Creates an `inverse` block for a [can.stache.helper helper function]'s
[can.stache.helperOptions options argument]'s `inverse` property.

@param {can.stache} INVERSE a stache template coverted to a
function and set as the [can.stache.helper helper function]'s
[can.stache.helperOptions options argument]'s `inverse` property.

@body

## Use

For more information on how `{{else}}` is used checkout:

 - [can.stache.helpers.if {{if key}}]
 - [can.stache.tags.sectionHelper {{#helper}}]

