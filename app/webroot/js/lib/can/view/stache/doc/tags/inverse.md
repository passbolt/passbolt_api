@function can.stache.tags.inverse {{^key}}
@parent can.stache.tags 5

@signature `{{^key}}BLOCK{{/key}}`

Render blocks of text if the value of the key
is falsey.  An inverted section syntax is similar to regular
sections except it begins with a caret rather than a
pound. If the value referenced is falsey, the section will render.

@param {can.stache.key} key A key that references a value within the current or parent
[can.stache.context context]. If the value is a function or [can.compute], the
function's return value is used.

@return {String}

Depending on the value's type, the following actions happen:

- A `truthy` value - the block is not rendered.
- A `falsey` value - the block is rendered.

The rendered result of the block or an empty string is returned.

@body

## Use

Inverted sections match falsey values. An inverted section
syntax is similar to regular sections except it begins with a caret
rather than a pound. If the value referenced is falsey, the section
will render. For example:


The template:

    <ul>
        {{#friends}}
            </li>{{name}}</li>
        {{/friends}}
        {{^friends}}
            <li>No friends.</li>
        {{/friends}}
    </ul>

And data:

    {
        friends: []
    }

Results in:


    <ul>
        <li>No friends.</li>
    </ul>

