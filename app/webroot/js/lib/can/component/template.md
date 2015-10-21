@property {String|can.view.renderer} [can.Component.prototype.template]
@parent can.Component.prototype

Provides a template to render directly within the component's tag. The template is rendered with the
component's [can.Component::viewModel viewModel].  `<content>` elements within the template are replaced by
the source elements within the component's tag.

@option {String} The string contents of a [can.mustache] template.  For example:

    can.Component({
      tag: "my-tabs",
      template: "<ul>{{#panels}}<li>{{title}}</li> ..."
    })

@option {can.view.renderer} A [can.view.renderer] returned by [can.mustache] or 
[can.view]. For example:

    can.Component({
      tag: "my-tabs",
      template: can.view("/ui/components/my-tabs.mustache")
    })

@body


## Use

The mustache template specified by the `template` property works similar to 
the [http://www.w3.org/TR/shadow-dom/ W3C Shadow DOM proposal]. It represents the contents
of a custom element, while being able to reposition the user provided __source__ elements
with the `<content>` tag.

There are three things to understand about a [can.Component]'s template:

 - It is inserted into the component's tag.
 - It is rendered with access to the component instance's viewModel.
 - `<content>` tags within the template act as insertion points for the source elements.

The following example demonstrates all three features:

@demo can/component/examples/my_greeting_full.html

The following explains how each part works:

__can.Component:__

    can.Component({
      "tag": "my-greeting",
      template: "<h1><content/></h1>",
      viewModel: {
        title: "can.Component"
      }
    })

This registers a component for elements like `<my-greeting>`. Its template
will place an `<h1>` element directly within `<my-greeting>` and put
the original contents of `<my-greeting>` within the `<h1>`. The component's
[can.Component::viewModel viewModel] adds a title value.

__Source template:__

    <header>
      <my-greeting>
         {{site}} - {{title}}
      </my-greeting>
    </header>

The source template is the template that 
uses `<my-greeting>`.  In the demo, this is defined within a `<script>` 
tag.

Notice:

 - There is content within `<my-greeting>`..
 - The content looks for a `site` and `title` value.

__Source data:__

    can.view("source-template",{
      site: "CanJS"
    })

This is how we render the source template that uses `<my-greeting>`. Notice
that the template is rendered with `site` in its [can.view.viewModel viewModel].

__HTML Result:__

    <header>
      <my-greeting>
        <h1>CanJS - can.Component</h1>
      </my-greeting>
    </header>

This is the result of the template transformations.  Notice that the
content within the original `<my-greeting>` is placed within the `<h1>` 
tag.  Also, notice that the original content is able to access data from
the source data and from the component's viewModel.
 
The following sections break this down more.


## Template insertion

The [can.mustache] template specified by template is rendered directly withing the custom tag.

For example the following component:

    can.Component({
      tag: "my-greeting",
      template: "<h1>Hello There</h1>"
    });

With the following source html:

    <header>
      <my-greeting></my-greeting>
    </header>

Produces the following html:

    <header>
      <my-greeting><h1>Hello There</h1></my-greeting>
    </header>

However, if there was existing content within the source html like:

    <header>
      <my-greeting>DO REMOVE ME!!!</my-greeting>
    </header>

That content is removed and replaced by the component's template:

    <header>
      <my-greeting><h1>Hello There</h1></my-greeting>
    </header>

### The `<content>` element

Use the `<content>` element to place the source content in the 
component's element within the component's 
template. For example, if we change the component to look like:

    can.Component({
      tag: "my-greeting",
      template: "<h1><content/></h1>"
    });

And rendered with source html like:

    <my-greeting>Hello World</my-greeting>

Produces:

    <my-greeting><h1>Hello World</h1></my-greeting>

### `<content>` element default content

If the user does not provide source content, the html 
between the `<content>` tags will be used. For example, if we 
change the component to look like:

    can.Component({
      tag: "my-greeting",
      template: "<h1><content>Hello World</content></h1>"
    });

And rendered with source html like:

    <my-greeting></my-greeting>

Produces:

    <my-greeting><h1>Hello World</h1></my-greeting>

