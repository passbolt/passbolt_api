@function can.stache.helpers.unless {{#unless key}}
@parent can.stache.htags 4

@signature `{{#unless key}}BLOCK{{/unless}}`

Render the block of text if the key's value is falsey.

@param {can.stache.key} key A key that references a value within the current or parent
context. If the value is a function or can.compute, the function's
return value is used.

@param {can.stache} BLOCK A template that is rendered
if the `key`'s value is falsey.

@body

The `unless` helper evaluates the inverse of the value
of the key and renders the block between the helper and the slash.

    {{#unless expr}}
      // unless
    {{/unless}}
