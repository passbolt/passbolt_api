@function can.stache.tags.close {{/key}}
@parent can.stache.tags 4

@signature `{{/key}}`

Ends a [can.stache.tags.section {{#key}}] or [can.stache.tags.sectionHelper {{#helper}}]
block.

@param {can.stache.key} [key] A key that matches the opening key or helper name. It's also
possible to simply write `{{/}}` to end a block.
