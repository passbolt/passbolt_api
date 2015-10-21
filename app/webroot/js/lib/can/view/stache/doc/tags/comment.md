@function can.stache.tags.comment {{!key}}

@parent can.stache.tags 7

@description A comment that doesn't get inserted into the rendered result.

@signature `{{!key}}`

The comment tag operates similarly to a `<!-- -->` tag in HTML. It exists in your template but never shows up.

@param {can.stache.key} key Everything within this tag is completely ignored.
@return {String}