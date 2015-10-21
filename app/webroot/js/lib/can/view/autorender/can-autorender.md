@module {Attribute} can/view/autorender.can-autorender can-autorender
@parent can.view.plugins

Mark a script or other element as a template that should be 
rendered automatically.

@signature `<script can-autorender type='text/TYPE'>CONTENT< /script>`

Renders the content of the script tag with a specified content.

@param {String} TYPE The template engine type.  It should be one
of `ejs`, `mustache` and `stache`.

@param {String} CONTENT The content to be rendered.

@signature `<TAG can-autorender type='text/TYPE'>CONTENT</TAG>`

Renders the contents of the element as a template and replaces the 
original contents. Note: This does not currently work perfectly with 
any current templating engine.  However, this will likely change.

@param {String} TYPE The template engine type.  It should be one
of `ejs`, `mustache` and `stache`.

@body

## Use

Read more about using this tag on [can/view/autorender].
