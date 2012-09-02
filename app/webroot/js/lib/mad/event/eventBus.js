steal(
	MAD_ROOT + '/controller/controller.js',
	MAD_ROOT + '/event/eventable.js'
).then(function ($) {

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
		}

	});

	mad.event.EventBus.augment('mad.event.Eventable');

});