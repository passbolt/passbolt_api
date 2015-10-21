@module can/view/stache/system system
@parent can.stache.plugins

A [SystemJS](https://github.com/systemjs/systemjs) and [StealJS](http://stealjs.com) extension
that allows stache templates as dependencies.

@signature `STACHE_MODULE_NAME!can/view/stache/system`

  @param {moduleName} STACHE_MODULE_NAME The module name of a stache template. This
  will typically be something like `templates/stache`.

  @return {can.view.renderer} A renderer function that will render the template into a document fragment.
  
@body

## StealJS Use

With [StealJS](http://stealjs.com) used from `node_modules`, CanJS will configure
SystemJS so stache modules can be loaded by just placing a `!` following a moduleName that
ends with `.stache` like:

```js
import todosStache from "todos.stache!"
todosStache([{name: "dishes"}]) //-> <documentFragment>
```

## Specifying Dependencies

This plugin allows [can/view/stache/system.import &lt;can-import&gt;] elements that specify 
template dependencies:


```
<can-import from="components/my_tabs"/>
<can-import from="helpers/prettyDate"/>

<my-tabs>
  <my-panel title="{{prettyDate start}}">...</my-panel>
  <my-panel title="{{prettyDate end}}">...</my-panel>
</my-tabs>
```