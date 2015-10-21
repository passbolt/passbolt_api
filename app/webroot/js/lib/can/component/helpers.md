@property {Object.<String,can.mustache.helper>} can.Component.prototype.helpers
@parent can.Component.prototype

Helper functions used with the component's template.

@option {Object.<String,can.mustache.helper>}

An object of [can.mustache] helper names and methods. The helpers are only
available within the component's template and source html. The helper's
are always called back with `this` as the [can.Component::viewModel viewModel].

@body

## Use

[can.Component]'s helper object lets you provide helper functions that are localized to
the component's [can.Component::template template].  The following example
uses an `isSelected` helper to render content for selected items. Click
one of the following libraries to toggle them within the `selected` array. 

@demo can/component/examples/selected.html