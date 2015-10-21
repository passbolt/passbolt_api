@constructor can.Component
@download can/component
@test can/component/test.html
@parent canjs
@release 2.0
@link ../docco/component/component.html docco


@description Create widgets that use a template, a view-model 
and custom tags.

@signature `< TAG [ATTR-NAME="{KEY}|ATTR-VALUE"] >`

Create an instance of a component on a particular 
tag in a [can.stache] template.

@release 2.1

@param {String} TAG An HTML tag name that matches the [can.Component::tag tag]
property of the component.

@param {String} ATTR-NAME An HTML attribute name. Any attribute name is
valid. Any attributes added to the element are added as properties to the
component's [can.Component::viewModel viewModel]. In the DOM, attribute names
are case insensitive.  To pass a camelCase attribute to the component's viewModel,
hypenate the attribute name like:

    <tag attr-name="{key}"></tag>

This will set `attrName` on the component's viewModel.

@param {can.stache.key} [KEY] Specifies the value of a property passed to
the component instance's [can.Component::viewModel viewModel] that will be looked
up in the [can.view.Scope can.stache scope]. 

@param {can.stache.key} [ATTR-VALUE] If the attribute value is not
wrapped with `{}`, the string value of the attribute will be
set on the component's viewModel.

@signature `< TAG [ATTR-NAME=KEY|ATTR-VALUE] >`

Create an instance of a component on a particular 
tag in a [can.mustache] template.

@param {String} TAG An HTML tag name that matches the [can.Component::tag tag]
property of the component.

@param {String} ATTR-NAME An HTML attribute name. Any attribute name is
valid. Any attributes added to the element are added as properties to the
component's [can.Component::viewModel viewModel].

@param {can.mustache.key} [ATTR-VALUE] Specifies the value of a property passed to
the component instance's [can.Component::viewModel viewModel]. By default `ATTR-VALUE`
values are looked up in the [can.view.viewModel can.mustache viewModel]. If the string value
of the `ATTR-NAME` is desired, this can be specified like: 

    ATTR-NAME: "@"

@param {can.mustache.key} [KEY] Specifies the value of a property passed to
the component instance's [can.Component::viewModel viewModel] that will be looked
up in the [can.view.Scope can.stache scope].



@body

## Use

Watch this video for an overview of can.Component, why you should use it, and a hello world example:

<iframe width="662" height="372" src="https://www.youtube.com/embed/BM1Jc3lVUrk" frameborder="0" allowfullscreen></iframe>

This video provides a more in depth overview of the API and goes over several examples of can.Components:

<iframe width="662" height="372" src="https://www.youtube.com/embed/ogX765S4iuc" frameborder="0" allowfullscreen></iframe>

Note: the videos above reference the `scope` property, which was replaced by the [can.Component::viewModel viewModel] property in 2.2.

To create a `can.Component`, you must first [can.Component.extend extend] `can.Component`
with the methods and properties of how your component behaves:

    can.Component.extend({
      tag: "hello-world",
      template: "{{#if visible}}{{message}}{{else}}Click me{{/if}}",
      viewModel: {
        visible: false,
        message: "Hello There!"
      },
      events: {
        click: function(){
        	this.viewModel.attr("visible", !this.viewModel.attr("visible") );
        }
      }
    });

This element says "Click me" until a user clicks it and then 
says "Hello There!".  To create a a instance of this component on the page, 
add `<hello-world></hello-world>` to a mustache template, render
the template and insert the result in the page like:

    var template = can.mustache("<hello-world></hello-world>");
    $(document.body).append( template() );

Check this out here:

@demo can/component/examples/click_me.html



Typically, you do not append a single component at a time.  Instead, 
you'll render a template with many custom tags like:

    <srchr-app>
      <srchr-search models="models">
        <input name="search"/>
      </srchr-search>
      <ui-panel>
        <srchr-history/>
        <srchr-results models="models"/>
      </ui-panel>
    </srchr-app>

### Creating a can.Component

Use [can.Component.extend] to create a `can.Component` constructor function
that will automatically get initialized whenever the component's tag is 
found. 

Note that inheriting from components works differently than other CanJS APIs. You can't call `.extend` on a particular component to create a "subclass" of that component. 

Instead, components work more like HTML elements. To reuse functionality from a base component, build on top of it with parent components that wrap other components in their template and pass any needed viewModel properties via attributes.

### Tag

A component's [can.Component::tag tag] is the element node name that
the component will be created on.


The following matches `<hello-world>` elements.

    can.Component.extend({
      tag: "hello-world"
    });

### Template

A component's [can.Component::template template] is rendered as
the element's innerHTML.

The following component:

    can.Component.extend({
      tag: "hello-world",
      template: "<h1>Hello World</h1>"
    });

Changes `<hello-world></hello-world>` elements into:

    <hello-world><h1>Hello World</h1></hello-world>

Use the `<content/>` tag to position the custom element's source HTML.

The following component:

    can.Component.extend({
      tag: "hello-world",
      template: "<h1><content/></h1>"
    });

Changes `<hello-world>Hi There</hello-world>` into:

    <hello-world><h1>Hi There</h1></hello-world>

### viewModel

A component's [can.Component::viewModel viewModel] defines a can.Map that
is used to render the component's template. The maps properties 
are typically set by attributes on the custom element's 
HTML. By default, every attribute's value is looked up in the parent viewModel
of the custom element and added to the viewModel object.

The following component:

    can.Component.extend({
      tag: "hello-world",
      template: "<h1>{{message}}</h1>"
    });

Changes the following rendered template:

    var template = can.mustache("<hello-world message='greeting'/>");
    template({
      greeting: "Salutations"
    })

Into:

    <hello-world><h1>Salutations</h1></hello-world>

Default values can be provided. The following component:

    can.Component.extend({
      tag: "hello-world",
      template: "<h1>{{message}}</h1>",
      viewModel: {
        message: "Hi"
      }
    });

Changes the following rendered template:

    var template = can.mustache("<hello-world message='greeting'/>");
    template({})

Into:

    <hello-world><h1>Hi</h1></hello-world>

If you want to set the string value of the attribute on viewModel, give viewModel a
default value of "@".  The following component:

    can.Component.extend({
      tag: "hello-world",
      template: "<h1>{{message}}</h1>",
      viewModel: {
        message: "@"
      }
    });

Changes the following rendered template:

    var template = can.mustache("<hello-world message='Howdy'/>");
    template({})

Into:

    <hello-world><h1>Howdy</h1></hello-world>

### Events

A component's [can.Component::events events] object is used to listen to events (that are not
listened to with [can.view.bindings view bindings]). The following component
adds "!" to the message every time `<hello-world>` is clicked:

    can.Component.extend({
      tag: "hello-world",
      template: "<h1>{{message}}</h1>",
      events: {
        "click" : function(){
          var currentMessage = this.viewModel.attr("message");
          this.viewModel.attr("message", currentMessage+ "!")
        }
      }
    });

Components have the ability to bind to special [can.events.inserted inserted] and [can.events.removed removed] events that are called when a component's tag has been inserted into or removed from the page.

### Helpers

A component's [can.Component::helpers helpers] object provides [can.mustache.helper mustache helper] functions
that are available within the component's template.  The following component
only renders friendly messages:

    can.Component.extend({
      tag: "hello-world",
      template: "{{#isFriendly message}}"+
                  "<h1>{{message}}</h1>"+
                "{{/isFriendly}}",
      helpers: {
        isFriendly: function(message, options){
          if( /hi|hello|howdy/.test(message) ) {
            return options.fn();
          } else {
            return options.inverse();
          }
        }
      }
    });

## Differences between components in can.mustache and can.stache

A [can.mustache] template passes values from the viewModel to a `can.Component`
by specifying the key of the value in the attribute directly.  For example:

    can.Component.extend({
      tag: "my-tag",
      template: "<h1>{{greeting}}</h1>"
    });
    var template = can.mustache("<my-tag greeting='message'></my-tag>");
    
    var frag = template({
      message: "Hi"
    });
    
    frag //-> <my-tag greeting='message'><h1>Hi</h1></my-tag>
   
With [can.stache], you wrap the key with `{}`. For example:

    can.Component.extend({
      tag: "my-tag",
      template: "<h1>{{greeting}}</h1>"
    });
    var template = can.stache("<my-tag greeting='{message}'></my-tag>");
    
    var frag = template({
      message: "Hi"
    });
   
    frag //-> <my-tag greeting='{message}'><h1>Hi</h1></my-tag>

If the key was not wrapped, the template would render:

    frag //-> <my-tag greeting='message'><h1>message</h1></my-tag>
 
Because the attribute value would be passed as the value of `greeting`.

## Examples

Check out the following examples built with `can.Component`.

### Tabs

The following demos a tabs widget.  Click "Add Vegetables"
to add a new tab.

@demo can/component/examples/tabs.html

An instance of the tabs widget is created by creating `<tabs>` and `<panel>`
elements like:

    <tabs>
      {{#each foodTypes}}
        <panel title='title'>{{content}}</panel>
      {{/each}}
    </tabs>

To add another panel, all we have to do is add data to `foodTypes` like:

    foodTypes.push({
      title: "Vegetables",
      content: "Carrots, peas, kale"
    })

The secret is that the `<panel>` element listens to when it is inserted
and adds its data to the tabs' list of panels with:

    this.element.parent().viewModel().addPanel( this.viewModel );

### TreeCombo

The following tree combo lets people walk through a hierarchy and select locations.

@demo can/component/examples/treecombo.html

The secret to this widget is the viewModel's `breadcrumb` property, which is an array
of items the user has navigated through, and `selectableItems`, which represents the children of the
last item in the breadcrub.  These are defined on the viewModel like:


    breadcrumb: [],
    selectableItems: function(){
      var breadcrumb = this.attr("breadcrumb");
	      	
      // if there's an item in the breadcrumb
      if(breadcrumb.attr('length')){
			
        // return the last item's children
        return breadcrumb.attr(""+(breadcrumb.length-1)+'.children');
      } else{
		    
        // return the top list of items
        return this.attr('items');
      }
    }

When the "+" icon is clicked next to each item, the viewModel's `showChildren` method is called, which
adds that item to the breadcrumb like:

    showChildren: function( item, el, ev ) {
      ev.stopPropagation();
      this.attr('breadcrumb').push(item)
    },

### Paginate

The following example shows 3 
widget-like components: a grid, next / prev buttons, and a page count indicator. And,
it shows an application component that puts them all together.

@demo can/component/examples/paginate.html

This demo uses a `Paginate` can.Map to assist with maintaining a paginated state:

    var Paginate = can.Map.extend({
    ...
    });
    
The `app` component creates an instance of the `Paginate` model
and a `websitesDeferred` that represents a request for the Websites
that should be displayed.

    viewModel: function () {
      return {
        paginate: new Paginate({
          limit: 5
        }),
        websitesDeferred: can.compute(function () {
          var params = {
            limit: this.attr('paginate.limit'),
            offset: this.attr('paginate.offset')
          },
            websitesDeferred = Website.findAll(params),
            self = this;

          websitesDeferred.then(function (websites) {
            self.attr('paginate.count', websites.count)
          });
    
          return websitesDeferred;
        })
      }
    }

The `app` control passes paginate, paginate's values, and websitesDeferreds to
its sub-components:

    <grid deferredData='websitesDeferred'>
      {{#each items}}
        <tr>
          <td width='40%'>{{name}}</td>
          <td width='70%'>{{url}}</td>
        </tr>
      {{/each}}
    </grid>
    <next-prev paginate='paginate'/>
    <page-count page='paginate.page' count='paginate.pageCount'/>

## IE 8 Support

While CanJS does support Internet Explorer 8 out of the box, if you decide
to use `can.Component` then you will need to include [HTML5 Shiv](https://github.com/aFarkas/html5shiv)
in order for your custom tags to work properly.

For namespaced tag names (e.g. `<can:example>`) and hyphenated tag names (e.g. `<can-example>`) to work properly, you will need to use version 3.7.2 or later.