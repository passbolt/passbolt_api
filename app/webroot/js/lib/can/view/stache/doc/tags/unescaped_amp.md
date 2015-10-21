@function can.stache.tags.unescaped2 {{&key}}

@parent can.stache.tags 2

@description Insert the unescaped value of the [can.stache.key key] into the
output of the template.

@signature `{{&key}}`

The `{{&key}}` tag is an alias for [can.stache.tags.unescaped {{{key}}}], behaving just
like [can.stache.tags.escaped {{key}}] and [can.stache.helpers.helper {{helper}}] but does not
escape the result.

@param {can.stache.key} key A key that references a value within the current or parent
context. If the value is a function or can.compute, the function's return value is used.
@return {String|Function|*}

