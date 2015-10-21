@module can/view/stache/system.import <can-import>
@parent can.stache.plugins

@signature `<can-import from="MODULE_NAME"/>`

@param {moduleName} [MODULE_NAME] A module that this template depends on.

@body

## Use

A template might depend on component or helper modules. `<can-import>` allows
you to specify these dependencies.

Example:

```
<can-import from="components/my_tabs"/>
<can-import from="helpers/prettyDate"/>

<my-tabs>
  <my-panel title="{{prettyDate start}}">...</my-panel>
  <my-panel title="{{prettyDate end}}">...</my-panel>
</my-tabs>
```

Currently this __only__ works with [can/view/autorender] or the [can/view/stache/system] plugin.

