@module {function()} can/view/autorender can.autorender
@parent can.view.plugins

A module that automatically renders script and other elements with 
the [can/view/autorender.can-autorender] attribute. This function is useful to know when 
the templates have finished rendering.

@signature `can.autorender(succcess, error)`

  Registers functions to callback when all templates successfully render or an error in rendering happens.

  @param {function} success A function to callback when all autorendered templates have been rendered
  successfully.
  
  @param {function} [error] A function to callback if a template was not rendered successfully.
  
@body

## Use

As long is this module is part of your CanJS build or imported with RequireJS, StealJS, or SystemJS,
[can/view/autorender.can-autorender] will automatically look for `can-autorender` tags and render them.  Once
all templates have finished rendering, it will call any callbacks passed to `can.autorender()`.


For example, you might have a page like:

```
<body>
  <script type='text/stache' can-autorender id='main'
    message="Hello World">
    <my-component>
      {{message}}
    </my-component>
  </script>
  
  <script src='jquery.js'></script>
  <!-- A CanJS build that includes this plugin -->
  <script src='can.custom.js'></script>
  <!-- All your app's code and components -->
  <script src='app.js'></script>
  <script>
    // Wait until everything has rendered.
    can.autorender(function(){
      
      // Update the viewModel the template was rendred with:
      $("#main").viewModel().attr("message","Rendered!");
      
    })
  </script>
</body>
```

## Rendered placement

If the template source is a `<script>` tag within the `<body>`, the rendered template is placed
immediately following the template. 

For example:

```
<body>
  <script type='text/stache' can-autorender message="Hi">
    {{message}}!
  </script>
  <div>...</div>
</body>
```

Becomes:

```
<body>
  <script type='text/stache' can-autorender message="Hi">
    {{message}}!
  </script>
  Hi!
  <div>...</div>
</body>
```

If the `<script>` tag is outside the body, for example in the `<head>`
tag, the rendered result will be placed just before the closing `</body>` tag.

For example:

```
<head>
  <script type='text/stache' can-autorender message="Hi">
    {{message}}!
  </script>
</head>
<body>
  <div>...</div>
</body>
```

Becomes:

```
<head>
  <script type='text/stache' can-autorender message="Hi">
    {{message}}!
  </script>
</head>
<body>
  <div>...</div>
  Hi!
</body>
```

If the template source is any other element, the element's contents will be replaced with the rendered result.  For example:

```
<body>
  <div type='text/stache' can-autorender message="Hi">
    {{message}}!
  </div>
</body>
```

Becomes:

```
<body>
  <div type='text/stache' can-autorender message="Hi">
    Hi!
  </div>
</body>
```

## Scope

The template is rendered with a [can.Map] made from the attributes of the 
template source element.  That `map` is available on the 
template source element via [can.viewModel].  You can 
change the map at any time:

```
<body>
  <script type='text/stache' can-autorender id='main'>
    {{message}}!
  </script>
  <script>
    var viewModel = can.viewModel(document.getElementById("main"));
    viewModel.attr("message","Hello There!");
  </script>
</body>
```

You can change attributes on the element and it will update the 
viewModel too:

```
<body>
  <script type='text/stache' can-autorender id='main'>
    {{message}}!
  </script>
  <script>
    var main = document.getElementById("main");
    main.setAttribute("message","Hello There!");
  </script>
</body>
```



## StealJS Use

For demo pages that require very little setup:

```
<body>
  <script type='text/stache'>
    <can-import from="components/my-component"/>
    <my-component>
      {{message}}
    </my-component>
  </script>
  <script src='../node_modules/steal/steal.js' 
          main='can/view/autorender/'>
  </script>
</body>
```

For demo pages that require a little custom setup:

```
<body>
  <script type='text/stache' can-autorender>
    <can-import from="components/my-component"/>
    <my-component>
      {{message}}
    </my-component>
  </script>
  <script src='../node_modules/steal/steal.js' 
          main='@empty'>
  </script>
  <script>
    steal('can','jquery','can/view/autorender', function(can, $){
      $("my-component").viewModel().attr("message", "Hi");
    });
  </script>
</body>
```





## Errors

Error callbacks will be called if a template has a parsing error or
a [can/view/stache/system.import] fails.
