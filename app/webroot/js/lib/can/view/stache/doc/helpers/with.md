@function can.stache.helpers.with {{#with key}}
@parent can.stache.htags 6

@signature `{{#with key}}BLOCK{{/with}}`

Changes the context within a block.

@param {can.stache.key} key A key that references a value within the current or parent
context. If the value is a function or can.compute, the function's
return value is used.

@param {can.stache} BLOCK A template that is rendered
with the context of the `key`'s value.

@body

Stache typically applies the context passed in the section
at compiled time.  However, if you want to override this
context you can use the `with` helper.

    {{#with arr}}
      // with
    {{/with}}
