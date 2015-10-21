@function can.stache.tags.unescaped {{{key}}}

@parent can.stache.tags 1

@description Insert the unescaped value of the [can.stache.key key] into the
output of the template.

@signature `{{{key}}}`

Behaves just like [can.stache.tags.escaped {{key}}] and [can.stache.helpers.helper {{helper}}] but does not
escape the result.

@param {can.stache.key} key A key that references a value within the current or parent
context. If the value is a function or can.compute, the function's return value is used.
@return {String|Function|*}

