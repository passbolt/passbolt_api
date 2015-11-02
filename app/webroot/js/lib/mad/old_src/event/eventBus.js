steal(
	'mad/controller/controller.js',
	'mad/event/eventable.js'
).then(function () {

	/*
	 * @class mad.event.EventBus
	 * @inherits mad.controller.Controller
	 * @parent mad.core
	 *
	 * The Event Bus is the communication heart of the application.
	 * The Event Bus is a mechanism for 1) publishing events and 2) observing a subset of these
	 * events.
	 *
	 * As the Event Bus is a Controller, it is implicitly associated to a DOM Element when it is
	 * instanciated. The Event Bus will take advantages of the situation and uses the DOM events
	 * mechanism to carry the transmissions.
	 *
	 * To trigger an event you have to call the mad.bus functions directly
	 * mad.bus.trigger('EVT_NAME', EventOptsArray);
	 *
	 * To observe an event it exists several solutions.
	 *
	 * 1) By using jQuery :
	 * $(mad.bus).on('EVT_NAME', function() { ... });
	 *
	 * 2) By using the magic binding functions (templated) of canJS
	 * '{mad.bus} EVT_NAME': function(evtOpts) { ... }
	 *
	 * 3) By using the bind function of the class
	 * mad.bus.bind('EVT_NAME', function(evtOpts) { ... });
	 *
	 * To trigger a request you have to call the mad.bus functions directly
	 * mad.bus.triggerRequest('RQST_NAME', RqstOptsArray);
	 *
	 * To observe a request it exists several solutions.
	 *
	 * 1) By using jQuery :
	 * $(mad.bus).on('RQST_NAME', function(promise, evtOpts) { ... });
	 *
	 * 2) By using the magic binding functions (templated) of canJS
	 * '{mad.bus} RQST_NAME': function(el, ev, promis, evtOpts) { ... }
	 *
	 * 3) By using the bind function of the class
	 * mad.bus.bind('RQST_NAME', function(promise, evtOpts) { ... });
	 *
	 * A promise is always passed as parameter of the observer function and has to be completed
	 * by the function which takes care of the request.
	 *
	 * @constructor
	 * Creates a new event bus controller
	 * @return {mad.event.EventBus}
	 */
	mad.controller.Controller.extend('mad.event.EventBus', /** @prototype */ {

		// Constructor like.
		'init': function () {
			// As the parent class is a Controller, and because all Controllers use the Event
			// Bus during their instanciation to notify that they alive. We should avoid the
			// inheritance system to execute the parent constructor code, as it will try to
			// work with itself and it is not fully instanciated !!!
			// @todo Define the limitation of this.
		},

		/**
		 * Trigger an event on the Event Bus.
		 *
		 * @param {String} eventName Event name
		 * @param {Array} eventData (Optional) Data to associate to the event. The data has to be
		 *  passed to the function as an array.
		 */
		'trigger': function (eventName, eventData) {
			var data = typeof eventData != 'undefined' ? eventData : [];

			// Trigger the event on the bus.
			this.element.trigger(eventName, data);

			// Make the passbolt plugin able to catch the application event.
			// Rhino does not understand these primitives.
			if (!steal.isRhino) {
				var event = document.createEvent('CustomEvent');
				event.initCustomEvent(eventName, true, true, data);
				document.documentElement.dispatchEvent(event);
			}
		},

		/**
		 * Trigger a request on the Event Bus.
		 *
		 * @param {String} eventName Event name
		 * @param {Array} eventData (Optional) Data to associate to the event. The data has to be
		 *  passed to the function as an array.
		 *
		 * @return {jQuery.Deferred.Promise} Return a promise to the caller.
		 */
		'triggerRequest': function (rqstName, rqstData) {
			var data = [],
				deferred = $.Deferred();

			// The request data are in the expected format.
			if (Object.prototype.toString.call(rqstData) == "[object Array]") {
				data = rqstData;
			}
			// If object format given, format it in array
			else if (Object.prototype.toString.call(rqstData) == "[object Object]") {
				data = [rqstData];
			}

			// Observers of this request expect a promise as first parameter.
			data.unshift(deferred);

			// Trigger the request on the Event Bus.
			this.trigger(rqstName, data);

			// Return the promise to the caller.
			return deferred.promise();
		},

		/**
		 * Bind an event on the associated DOM element.
		 *
		 * @param {String} eventName Event name
		 * @param {function} func The function to execute when the event is fired
		 * @return {void}
		 */
		'bind': function (eventName, func) {
			this.element.bind(eventName, func);
		}

	});

	// Observe the addon-message and forward them to the eventBus.
	window.addEventListener("addon-message", function (event) {
		mad.bus.element.trigger(event.detail.event, event.detail.data);
	}, false);
});
