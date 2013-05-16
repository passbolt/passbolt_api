@page mad.controller.component Components
@tag mad.controller.component
@parent index
@see mad.controller.ComponentController
@see mad.model.ComponentState

Component controller is our representation of graphical controllers. It is linked to a view 
that it is controlling. An other aspect of the component controller is that it is implementing
a state system to make the development of the behaviors cleaner.

## Linked view
Each component controller can be rendered following these strategies :

* Use the default View
* Use a custom view

### The default View
By default the Component controller uses the provided default view [mad.view.View|mad.view.View].
The view is initialized in the component controller constructor and rendered in the 
[mad.controller.ComponentController.prototype.render|render] function of this one.
	
The view is using the EmbedJS engine to render components. Our view classes are automatically binded
to a template file based on the name of the component controller. By instance for the following
<i>mad.controller.component.MyComponent</i> component controller class the associated template will be
	
@codestart
lib/mad/view/template/controller/component/myComponent.ejs
@codeend
	
You can override this template uri by setting the optional parameter <i>templateUri</i>.
	
@codestart
var myComponent = new mad.controller.component.MyComponent($('#myComponent'), {
	'templateUri': 'mad/view/template/controller/component/myCustomTemplateUri.ejs'
});
@codeend

### The custom View
The framework is flexible and allow you to customize the view to use by the component controller to render
its view.
	
@codestart
var myComponent = new mad.controller.component.MyComponent($('#myComponent'), {
	'viewClass': mad.view.component.MyCustomViewComponent
});
@codeend
	
The custom view class mad.view.component.MyCustomViewComponent has to inherit the 
[mad.view.View|mad.view.View] class. You can implement in this class all the required view features.

## Component' states management
Each component controller behavior can be isolated and packaged in a specific function to make the 
code clear and reusable.

Each Component Controller instances embeds a [mad.model.ComponentState|mad.model.ComponentState] object
to manage its state. The Component Controller instances are listening changes from the Component State
model and they are updating their behavior following this process.

1. By default the Component Controller is entering in ready state when the Component is rendered or if
it is not a renderable component (Button, Input ...) when the instanciation process is finished.<br/><br/>
2. Implement the method which will carry the state
@codestart
'stateMyNewState': function (go) {
	if (go) {
		// Code to fire when the Component 
		// Controller is entering into MonkeyState
	} else {
		// Code to fire when the Component 
		// Controller is leaving MonkeyState
	}
}
@codeend
3. Switch to this new state by calling the method
@codestart
myComponentController.setState('myNewState');
@codeend
	
By default all Component Controllers have the following states :
	
* *loading* : the component is loading
* *ready* : the component is ready to interact with the user or the system
* *hidden* : the component is hidden

## Mad provides the following components out of the box :

* [mad.controller.component.ButtonController|Button]
* [mad.controller.component.CompositeController|Composite]
* [mad.controller.component.GridController|Grid]
* [mad.controller.component.PopupController|Popup]
* [mad.controller.component.TabController|Tab]
* [mad.controller.component.TreeController|Tree]
* [mad.controller.component.DynamicTreeController|DynamicTree]
* [mad.controller.component.MenuController|Menu]
* [mad.controller.component.DropDownMenuController|DropDownMenu]
* [mad.controller.component.WorkspaceController|Workspace]

## Example
@demo lib/mad/demo/controller/component.html
