steal(
	'mad/controller/controller.js',
	'mad/event/eventable.js'
).then(function () {

	/*
	 * @class mad.event.EventBus
	 * @inherits mad.controller.Controller
	 * @parent mad.core
	 * 
	 * The class EventBus allows developpers to create a bus to manage all events
	 * in a specific context. This bus will be attached to a DOM Node and developpers could
	 * fire and bind events on it.
	 * 
	 * @constructor
	 * Creates a new event bus controller
	 * @return {mad.event.EventBus}
	 */
	mad.controller.Controller.extend('mad.event.EventBus', /** @prototype */ {

		'init': function () {
			// the parent use the event bus controller, not the best way, but for the moment
			// do not call the parent init function
		},

		/**
		 * Trigger an event on the associated DOM element.
		 * @param {String} eventName Event name
		 * @param {Array} eventData (Optional) Parameters to send with the event 
		 * @return {void}
		 */
		'trigger': function (eventName, eventData) {
			var data = typeof eventData != 'undefined' ? eventData : {};
			this.element.trigger(eventName, data);
			// Trigger the event to the plugin
			var event = document.createEvent('CustomEvent');
			event.initCustomEvent(eventName, true, true, data);
			document.documentElement.dispatchEvent(event);
		},

		/**
		 * Bind an event on the associated DOM element.
		 * @param {String} eventName Event name
		 * @param {function} func The function to execute when the event is fired
		 * @return {void}
		 */
		'bind': function (eventName, func) {
			this.element.bind(eventName, func);
		}

	});

//	mad.event.EventBus.augment('mad.event.Eventable')

	// Listen to addon event, and dispatch them on the event bus.
	window.addEventListener("addon-message", function(event) {
		mad.bus.element.trigger(event.detail.event, event.detail.data);
	}, false);
});
