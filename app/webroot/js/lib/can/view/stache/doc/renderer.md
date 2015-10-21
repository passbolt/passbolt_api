@typedef {function(Object,Object.<String, function>):documentFragment} can.view.renderer(data,helpers) renderer

@description A function returned by [can.view], [can.ejs], [can.mustache], [can.stache] that renders a 
template into an html documentFragment.

@param {Object} data An object of data used to render the template.

@param {Object.<String, function>} helpers Local helper functions used by the template. 

@return {documentFragment} A documentFragment that contains the HTML rendered by the template.

@body

A "renderer" function is a function returned by various [can.view] APIs that can be used
to render data into a documentFragment.
