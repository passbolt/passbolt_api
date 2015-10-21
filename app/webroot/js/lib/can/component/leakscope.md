@property {Boolean} can.Component.prototype.leakScope
@parent can.Component.prototype

@description Allow reading the outer scope values from a component's template and
a component's viewModel values in the user content.

@option {Boolean}  `false` limits reading to:
 
- the component's viewModel from the component's template, and
- the outer scope values from the user content.

`true` adds the ability to read:

- the outer [can.view.Scope scope] values from the component's template, and
- the component's [can.Component.prototype.viewModel viewModel] values from the user content. 
 
The default value is `true`.  This may reverse in 3.0.

@body

## Use

A component's [can.Component::leakScope leakScope] option controls if a 
component's template can access the component's outer scope and the 
user content can read the component's view model.

Lets define what __outer scope__, __component's template__ and __user content__ mean.

If I have a `<hello-world>` component in a template like:

```
{{#data}}
	<hello-world>{{subject}}</hello-world>
{{/data}}
```

The __outer scope__ of `<hello-world>` has `data` as its context.  The __user content__ of
`<hello-world>` is the template between its tags.  In this case, the __user content__
is `{{subject}}`.

Finally, if `<hello-world>` is defined like:

```
can.Component.extend({
  tag: "hello-world",
  template: can.stache("{{greeting}} <content/>{{exclamation}}")
})
```

`{{greeting}} <content/>{{exclamation}}` represents the __component's template__.

## Example

If the following component is defined:

    can.Component.extend({
        tag: "hello-world",
        leakScope: true, // the default value
        template: can.stache("{{greeting}} <content/>{{exclamation}}"),
        viewModel: { subject: "LEAK", exclamation: "!" }
    })

And used like so:

    <hello-world>{{subject}}</hello-world>

With the following data in the outer scope:

    { greeting: "Hello", subject: "World"}

Will render the following if `leakScope` is true:

    <hello-world>Hello LEAK!</hello-world>

But if `leakScope` is false:

    <hello-world>Hello World</hello-world>

Because when the scope isn't leaked, the __component's template__ 
does not see `exclamation`. The __user content__ does not see the 
viewModel's `subject` and uses the outer scope's `subject` which is `"World"`.

Using the `leakScope: false` option is useful for hiding and protecting
internal details of `can.Component`, potentially preventing accidental
clashes.
