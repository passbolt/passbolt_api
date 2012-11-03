@function can.view
@parent canjs
@description A JavaScript template framework.

can.view is a JavaScript template framework that provides:

 - template loading from html elements or external files
 - synchronous and asynchronous template loading
 - deferred support
 - callbacks on elements for functionality like live live-binding
 
can.view supports other templating languages, but using [can.EJS] is highly encouraged.

## Use

`can.view( idOrUrl, data)` loads template content from an element or url, renders
it with data, and converts it to a documentFragment so it can be easily and 
efficiently inserted into the DOM.

    document.getElementById('person')
      .appendChild( can.view('person.ejs', {name: "Justin" } ) )

This code:

    
 1. Loads the template a 'mytemplate.ejs'. It might look like:
    <pre><code>&lt;h2>&lt;%= name %>&lt;/h2></pre></code>

 2. Renders it with {message: 'hello world'}, resulting in:
    <pre><code>&lt;div id='foo'>"&lt;h2>Justin&lt;/h2>&lt;/div></pre></code>

 3. Inserts the result into the foo element. Foo might look like:
    <pre><code>&lt;div id='person'>&lt;h2>Justin&lt;/h2>&lt;/div></pre></code>

## Loading Templates

`can.view` can load templates from a url or from a script.

### Loading templates from a script tag

To load from a script tag, create a script tag with:

 - the template contents within the script tag
 - an id
 - a type attribute that specifies the type of template

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

## Supported Template Engines

CanJS supports the following template languages:

- EmbeddedJS (ejs)
  <pre><code>&lt;h2>&lt;%= message %>&lt;/h2></code></pre>
  
- JAML (jaml)
  <pre><code>h2(data.message);</code></pre>
  
- Micro (micro)
  <pre><code>&lt;h2>{%= message %}&lt;/h2></code></pre>
  
- jQuery.Tmpl (tmpl)
  <pre><code>&lt;h2>${message}&lt;/h2></code></pre>

## Rendering to strings and sub-templates

To render to a string, use `can.view.render(idOrUrl, data)` like:

    can.view.render("/templates/recipe.ejs",{recipe: recipe})

To render a sub-template within another template, use render like:

    <% $.each(recipes, function(i, recipe){ %>
      <li><%== can.view.render("/templates/recipe.ejs",{
                 recipe: recipe
                }) %>
      </li>
    <% }) %>

## Asynchronous Loading

By default, retrieving templates is done synchronously. This 
is fine because StealJS packages view templates with your 
JS download.

However, some people might not be using StealJS or want to 
delay loading templates until necessary. If you have the need, 
you can provide a callback paramter like:

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
