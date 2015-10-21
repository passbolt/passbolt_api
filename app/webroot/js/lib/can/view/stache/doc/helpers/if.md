@function can.stache.helpers.if {{#if key}}
@parent can.stache.htags 2
@signature `{{#if key}}BLOCK{{/if}}`

Renders the `BLOCK` template within the current template.

@param {can.stache.key} key A key that references a value within the current or parent
context. If the value is a function or can.compute, the function's return value is used.

@param {can.stache} BLOCK A stache template.

@return {String} If the key's value is truthy, the `BLOCK` is rendered with the
current context and its value is returned; otherwise, an empty string.

@body

## Use

`{{#if key}}` provides explicit conditional truthy tests. For example,

The template:

    {{#if user.isFemale}}
      {{#if user.isMarried}}
        Mrs
      {{/if}}
      {{#if user.isSingle}}
        Miss
      {{/if}}
    {{/if}}

Rendered with:

    {user: {isFemale: true, isMarried: true}}

Results in:

    Mrs

If can be used with [can.stache.helpers.else {{else}}] too. For example,

    {{#if user.isFemale}}
      {{#if user.isMarried}}
        Mrs
      {{else}}
        Miss
      {{/if}}
    {{/if}}

Rendered with:

    {user: {isFemale: true, isMarried: false}}

Results in:

    Miss
