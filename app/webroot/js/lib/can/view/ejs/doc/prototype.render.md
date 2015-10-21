@function can.EJS.prototype.render render
@parent can.EJS.prototype 0
@description Render a view object with data and helpers.
@signature `ejs.render(data[, helpers])`
@param {Object} [data] The data to populate the template with.
@param {Object.<String, function>} [helpers] Helper methods referenced in the template.
@return {String} The template with interpolated data.

@body
Renders an object with view helpers attached to the view.

    var rendered = new can.ejs({text: "<h1><%= message %>"</h1>}).render({
      message: "foo"
    },{helper: function(){ ... }})

    console.log(rendered); // "<h1>foo</h1>"