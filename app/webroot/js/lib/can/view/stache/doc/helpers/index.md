@function can.stache.helpers.index {{@index}}

@parent can.stache.htags 10

@signature `{{@index [offset]}}`

Insert the index of an Array or can.List we are iterating on with [#each](can.stache.helpers.each)

@param {Number} offset The number to optionally offset the index by.

@body

## Use

When iterating over and array or list of items, you might need to render the index
of the item. Use the `@index` directive to do so. For example,

The template:

    <ul>
      {{#each items}}
        <li> {{@index}} - {{.}} </li>
      {{/each}}
    </ul>

Rendered with:

    { items: ['Josh', 'Eli', 'David'] }

Renders:

    <ul>
      <li> 0 - Josh </li>
      <li> 1 - Eli </li>
      <li> 2 - David </li>
    </ul>

