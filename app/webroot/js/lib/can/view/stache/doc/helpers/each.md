@function can.stache.helpers.each {{#each key}}
@parent can.stache.htags 5

@signature `{{#each key}}BLOCK{{/each}}`

Render the block of text for each item in key's value.

@param {can.stache.key} key A key that references a value within the current or parent
context. If the value is a function or can.compute, the function's
return value is used.

If the value of the key is a [can.List], the resulting HTML is updated when the
list changes. When a change in the list happens, only the minimum amount of DOM
element changes occur.

If the value of the key is a [can.Map], the resulting HTML is updated whenever
attributes are added or removed. When a change in the map happens, only
the minimum amount of DOM element changes occur.

@param {can.stache} BLOCK A template that is rendered for each item in
the `key`'s value. The `BLOCK` is rendered with the context set to the item being rendered.

@body

## Use

Use the `each` helper to iterate over a array
of items and render the block between the helper and the slash. For example,

The template:

    <ul>
      {{#each friends}}
        <li>{{name}}</li>
      {{/each}}
    </ul>

Rendered with:

    {friends: [{name: "Austin"},{name: "Justin"}]}

Renders:

    <ul>
      <li>Austin</li>
      <li>Justin</li>
    </ul>

## Object iteration

As of 2.1, you can now iterate over properties of objects and attributes with
the `each` helper. When iterating over [can.Map] it will only iterate over the
map's [keys](can.Map.keys.html) and none of the hidden properties of a can.Map. For example,

The template:

    <ul>
      {{#each person}}
        <li>{{.}}</li>
      {{/each}}
    </ul>

Rendered with:

    {person: {name: 'Josh', age: 27}}

Renders:

    <ul>
      <li>Josh</li>
      <li>27</li>
    </ul>
