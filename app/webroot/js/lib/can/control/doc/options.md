@property {Object} can.Control.prototype.options options
@parent can.Control.prototype
@description Options used to configure a control.

@body

The `this.options` property is an Object that contains
configuration data passed to a control when it is
created (`new can.Control(element, options)`).

In the following example, an options object with
a message is passed to a `Greeting` control. The
`Greeting` control changes the text of its [can.Control::element element]
to the options' message value.

	var Greeting = can.Control.extend({
		init: function(){
			this.element.text( this.options.message )
		}
	});

	new Greeting("#greeting",{message: "I understand this.options"});

The options argument passed when creating the control
is merged with [can.Control.defaults defaults] in
[can.Control.prototype.setup setup].

In the following example, if no message property is provided,
the defaults' message property is used.

	var Greeting = can.Control.extend({
		defaults: {
			message: "Defaults merged into this.options"
		}
	},{
		init: function(){
			this.element.text( this.options.message )
		}
	});

	new Greeting("#greeting");