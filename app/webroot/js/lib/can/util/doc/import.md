@function can.import
@parent can.util

Imports a module with the underlying module loader.

@param {String} moduleName The module name to load.  Example: `components/my-component`.
@return {Promise} A promise that resolves if the module was successfully loaded and
is rejected if the module can not be successfully loaded.

@body

## Use

`can.import` is used internally by [can/view/autorender] when it finds [can/view/stache/system.import &lt;can-import&gt;] tags
to import those modules before rendering the template.  


In the following example, `can.import` will import `"components/my-component"` prior to the template being 
rendered.


```html
 <script type='text/stache' can-autorender>
    <can-import from="components/my-component">
    <my-component>
      {{message}}
    </my-compoennt>
  </script>
```

## Module Loaders

`can.import` looks first for a `System` loader, followed by `require.amd`, followed by `steal` followed by a
CommonJS `require`.