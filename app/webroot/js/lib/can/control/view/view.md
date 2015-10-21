@page can.Control.view
@hide
@parent can.Control
@plugin can/control/view
@test can/control/view/test.html
@download http://donejs.com/can/dist/can.control.view.js

__DEPRECATED__

Renders a View template with the controller instance. If the first argument
is not supplied, it looks for a view in `/views/controlName/action_name.ejs`.
If data is not provided, it uses the controller instance as data.

	var Tasks = can.Control.extend('Tasks',{
		click: function( el ) {
			// renders with views/tasks/click.ejs with the control as data
			this.element.html( this.view() )

			// renders with views/tasks/click.ejs with some data
			this.element.html( 
				this.view({ name : 'The task' }) );

			// renders with views/tasks/under.ejs
			this.element.html( 
				this.view("under", [1,2]) );

			// renders with views/tasks/under.micro 
			this.element.html( 
				this.view("under.micro", [1,2]) );

			// renders with views/shared/top.ejs
			this.element.html( 
				this.view("shared/top", {phrase: "hi"}) );
		}
	})

The control name will be determined by its [can.Construct.fullName fullName] so
make sure that it is set when using the plugin. If the view name doesn't include an extension the
default view extension in [can.view.ext] will be added.