@function can.view
@parent canjs
@group can.view.static static
@group can.view.plugins plugins
@link ../docco/view/view.html docco

@description Utilities for 
loading, processing, rendering, and live-updating of templates.

@signature `can.view( idOrUrl, data[, helpers] )`

Loads a template, renders it with data and helper functions and returns 
the HTML of the template within 
a [https://developer.mozilla.org/en-US/docs/Web/API/DocumentFragment documentFragment].

    var frag = can.view(
        "/contact.ejs",
        {first: "Justin", last: "Meyer"},
        {
            fullName: function(first, last){
                return first +" "+ last
            }
        });
        
    document.getElementById('contacts').appendChild(frag)

@param {String|Object} idOrUrl The URL of a template or the id of a template embedded in a script tag or an object containing a `url` property for the URL to load and an `engine` property for the view engine (`mustache` or `ejs`) if it can't be infered from the file extensions or script tag type.  
@param {Object} data Data to render the template with.  
@param {Object.<String, function>+} helpers An object of named local helper functions.  
@return {documentFragment} The rendered result of the template converted to 
html elements within a [https://developer.mozilla.org/en-US/docs/Web/API/DocumentFragment documentFragment].

@signature `can.view( idOrUrl )`

Registers or loads a template and returns a [can.view.renderer renderer] function that can be used to
render the template with `data` and `helpers`.

    var renderer = can.view("/contact.ejs");
    
    var frag = renderer(
        {first: "Justin", last: "Meyer"},
        {
            fullName: function(first, last){
                return first +" "+ last
            }
        })
        
    document.getElementById('contacts').appendChild(frag)

@param {String} idOrUrl The URL of a template or the id of a template embedded in a script tag.

@return {can.view.renderer} A renderer function that can render the template into a documentFragment.

@body

## Use

`can.view( idOrUrl, data, helpers )` loads template content from an element, a url or a string, renders
it with data, and converts it to a documentFragment so it can be easily and 
efficiently inserted into the DOM.

    document.getElementById('person')
      .appendChild( can.view('person.ejs', {name: "Justin" } ) )

This code:

    
 1. Loads the template a 'mytemplate.ejs'. It might look like:
    <pre><code>&lt;h2>&lt;%= name %>&lt;/h2></pre></code>

 2. Renders it with {message: 'hello world'}, resulting in a [https://developer.mozilla.org/en-US/docs/Web/API/DocumentFragment documentFragment] that contains:
    <pre><code>&lt;h2>Justin&lt;/h2></pre></code>

 3. Inserts the result into the foo element. Foo might look like:
    <pre><code>&lt;div id='person'>&lt;h2>Justin&lt;/h2>&lt;/div></pre></code>

## Loading Templates

`can.view` can load templates from a url or from a script.

### Loading templates from a script tag

To load from a script tag, create a script tag with:

 - the template contents within the script tag
 - an id
 - a type attribute that specifies the type of template

For example:

    <script type='text/ejs' id='recipesEJS'>
      <% for(var i=0; i < recipes.length; i++){ %>
        <li><%=recipes[i].name %></li>
      <%} %>
    </script>

Render with this template like:

    document.getElementById('recipes')
      .appendChild( can.view('recipesEJS', recipeData ) )

Notice we passed the id of the element we want to render.

### Loading templates from a url

To load from a url, simply pass the location of the template
to `can.view`.  The location of the template needs an extension that
matches the type of template:

    document.getElementById('recipes')
      .appendChild( can.view('templates/recipes.ejs', recipeData ) )

Note: If you are using [RequireJS](http://requirejs.org/), the URL will be relative to its [`baseUrl`](http://requirejs.org/docs/api.html#config-baseUrl).

### Creating templates from strings

Create a template for a given id programmatically using
`can.view.<engine>(id, template)`:

    can.view.ejs('myViewEJS', '<h2><%= message %></h2>');
    can.view('myViewEJS', { message : 'Hello EJS' });
    // -> <h2>Hello EJS</h2>

It is also possible to get a nameless [can.view.renderer renderer] function when creating a template from a string:

    var renderer = can.view.ejs('<strong><%= message %></strong>');
    renderer({
      message : 'Message form EJS'
    }); // -> <strong>Message from EJS</strong>

    renderer = can.mustache('<strong>{{message}}</strong>');
    renderer({
      message : 'Message form Mustache'
    }); // -> <strong>Message from Mustache</strong>

## Supported Template Engines

CanJS supports the following live template languages:

- [can.ejs] EmbeddedJS 
  <pre><code>&lt;h2>&lt;%= message %>&lt;/h2></code></pre>

- [can.mustache] Mustache 
  <pre><code>&lt;h2{{message}}&lt/h2></code></pre>


## Rendering to strings and sub-templates

To render to a string, use `can.view.render(idOrUrl, data)` like:

    var str = can.view.render("/templates/recipe.ejs",{recipe: recipe});

To convert that rendered string into a live documentFragment, use [can.view.frag].

To render a [can.ejs] sub-template within another template, use render like:

    <% $.each(recipes, function(i, recipe){ %>
      <li><%== can.view.render("/templates/recipe.ejs",{
                 recipe: recipe
               }) %>
      </li>
    <% }) %>

## Asynchronous Loading

By default, retrieving templates is done synchronously. This 
is fine because [StealJS] packages view templates with your 
JS download.

However, some people might not be using StealJS or want to 
delay loading templates until necessary. If you have the need, 
you can provide a callback parameter like:

    can.view('recipes',recipeData, function(frag){
      document.getElementById('recipes')
        .appendChild(frag)
    });

The callback function will be called with the result of 
the rendered template.

## Deferreds 

If you pass deferreds to can.view it 
will wait until all deferreds resolve before rendering 
the view. This makes it a one-liner to make a request and use the 
result to render a template.

The following makes a request for todos in parallel with the 
todos.ejs template. Once todos and template have been loaded, 
it with render the view with the todos.

    can.view('recipes', Todo.findAll() , function(frag){
      document.getElementById('recipes')
        .appendChild(frag)
    })
