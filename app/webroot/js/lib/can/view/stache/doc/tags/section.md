/**
@function can.stache.tags.section {{#key}}
@parent can.stache.tags 3

@signature `{{#key}}BLOCK{{/key}}`

Render blocks of text one or more times, depending
on the value of the key in the current context.

@param {can.stache.key} key A key that references a value within the current or parent
[can.stache.context context]. If the value is a function or [can.compute], the
function's return value is used.


@return {String}

Depending on the value's type, the following actions happen:

- `Array` or [can.List] - the block is rendered for
  each item in the array. The [can.stache.context context] is set to
  the item within each block rendering.
- A `truthy` value - the block is rendered with the [can.stache.context context]
  set to the value.
- A `falsey` value - the block is not rendered.

The rendered result of the blocks, block or an empty string is returned.

@body

Sections contain text blocks and evaluate whether to render it or not.  If
the object evaluates to an array it will iterate over it and render the block
for each item in the array.  There are four different types of sections.

## Falseys or Empty Arrays

If the value returns a `false`, `undefined`, `null`, `""` or `[]` we consider
that a *falsey* value.

If the value is falsey, the section will **NOT** render the block.

    {
      friends: false
    }

    {{#friends}}
      Never shown!
    {{/friends}}


## Arrays

If the value is a non-empty array, sections will iterate over the
array of items, rendering the items in the block.

For example, a list of friends will iterate
over each of those items within a section.

    {
        friends: [
            { name: "Austin" },
            { name: "Justin" }
        ]
    }

    <ul>
        {{#friends}}
            <li>{{name}}</li>
        {{/friends}}
    </ul>

would render:

    <ul>
        <li>Austin</li>
        <li>Justin</li>
    </ul>

Reminder: Sections will reset the current context to the value for which it is iterating.
See the [basics of contexts](#Basics) for more information.

## Truthys

When the value is a non-falsey object but not a list, it is considered truthy and will be used
as the context for a single rendering of the block.

    {
        friends: { name: "Jon" }
    }

    {{#friends}}
        Hi {{name}}
    {{/friends}}

would render:

    Hi Jon!
