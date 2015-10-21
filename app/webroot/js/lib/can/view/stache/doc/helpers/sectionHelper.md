@function can.stache.helpers.sectionHelper {{#helper args hashes}}
@parent can.stache.htags 1

Calls a stache helper function with a block, and optional inverse
block.

@signature `{{#helper [args...] [hashName=hashValue...]}}BLOCK{{/helper}}`

Calls a stache helper function or a function with a block to
render.

The template:

    <p>{{#countTo number}}{{num}}{{/countTo}}</p>

Rendered with:

    {number: 5}

Will call the `countTo` helper:

    can.stache.registerHelper('madLib',
      function(number, options){
        var out = []
        for(var i =0; i < number; i++){
          out.push( options.fn({num: i+1}) )
        }
        return out.join(" ")
    });

Results in:

    <p>1 2 3 4 5</p>

@param {can.stache.key} helper A key that finds a [can.stache.helper helper function]
that is either [can.stache.registerHelper registered] or found within the
current or parent [can.stache.context context].

@param {...can.stache.key|String|Number} [args] Space seperated arguments
that get passed to the helper function as arguments. If the key's value is a:

 - [can.Map] - A getter/setter [can.compute] is passed.
 - [can.compute] - The can.compute is passed.
 - `function` - The function's return value is passed.

@param {String} hashProperty

A property name that gets added to a [can.stache.helperOptions helper options]'s
hash object.

@param {...can.stache.key|String|Number} hashValue A value that gets
set as a property value of the [can.stache.helperOptions helper option argument]'s
hash object.

@param {stache} BLOCK A stache template that gets compiled and
passed to the helper function as the [can.stache.helperOptions options argument's] `fn`
property.


@signature `{{#helper [args...] [hashName=hashValue...]}}BLOCK{{else}}INVERSE{{/helper}}`

Calls a stache helper function or a function with a `fn` and `inverse` block to
render.

The template:

    <p>The bed is
       {{isJustRight firmness}}
          pefect!
       {{else}}
          uncomfortable.
       {{/justRight}}</p>

Rendered with:

    {firmness: 45}

Will call the `isJustRight` helper:

    can.stache.registerHelper('isJustRight',
      function(number, options){
        if(number > 50){
          return options.fn(this)
        } else {
          return options.inverse(this)
        }
        return out.join(" ")
    });

Results in:

    <p>The bed is uncomfortable.</p>

@param {can.stache.key} helper A key that finds a [can.stache.helper helper function]
that is either [can.stache.registerHelper registered] or found within the
current or parent [can.stache.context context].

@param {...can.stache.key|String|Number} [args] Space seperated arguments
that get passed to the helper function as arguments. If the key's value is a:

 - [can.Map] - A getter/setter [can.compute] is passed.
 - [can.compute] - The can.compute is passed.
 - `function` - The function's return value is passed.

@param {String} hashProperty

A property name that gets added to a [can.stache.helperOptions helper options]'s
hash object.

@param {...can.stache.key|String|Number} hashValue A value that gets
set as a property value of the [can.stache.helperOptions helper option argument]'s
hash object.

@param {stache} BLOCK A stache template that gets compiled and
passed to the helper function as the [can.stache.helperOptions options argument's] `fn`
property.

@param {stache} INVERSE A stache template that gets compiled and
passed to the helper function as the [can.stache.helperOptions options argument's] `inverse`
property.


@body

## Use

Read the [use section of {{helper}}](can.stache.helpers.helper.html#section_Use) to better understand how:

 - [Helper functions are found](can.stache.helpers.helper.html#section_Arguments)
 - [Arguments are passed to the helper](can.stache.helpers.helper.html#section_Arguments)
 - [Hash values are passed to the helper](can.stache.helpers.helper.html#section_Hash)

Read how [helpers that return functions](can.stache.helper.html#section_Returninganelementcallbackfunction) can
be used for rich behavior like 2-way binding.


