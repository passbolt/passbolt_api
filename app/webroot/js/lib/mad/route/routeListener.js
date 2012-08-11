steal(MAD_ROOT + '/core/singleton.js')
.then( function ($) {

	/*
	 * @class mad.route.RouteListener
	 * The route listener will be one of the stone reference of the application.
	 * It will be the guarantor of the route change
	 * 
	 * @parent index
	 * @inherits mad.core.Singleton
	 * @constructor
	 * Creates a new route listener
	 * @return {mad.route.RouteListener}
	 */
	mad.core.Singleton.extend('mad.route.RouteListener',

	/** @static */

	{},

	/** @prototype */
	{
		'init': function () {
			this._super();

			var self = this;

			// load the routes to listen
			$.route(":extension/:controller/:action/:p1/:p2/:p3/:p4/:p5");
			$.route(":extension/:controller/:action/:p1/:p2/:p3/:p4");
			$.route(":extension/:controller/:action/:p1/:p2/:p3");
			$.route(":extension/:controller/:action/:p1/:p2");
			$.route(":extension/:controller/:action/:p1");
			$.route(":extension/:controller/:action");
			$.route(":extension/:controller");
			$.route(":extension");
			$.route("");
			$.route.ready();

			// listen the special haschange event, disptatch when a new route is comming
			// @note : Using the $.route.bind('change', function(){ ... }) is maybe the proper method, but it seems impossible to listen the whole change (extension+controler+action)
			$(window).bind('hashchange', function () {
				var route = self.getRoute();
				// if a route has been find, trigger the event on the event bus
				if (route != null) {
					if (mad.eventBus) mad.eventBus.trigger(mad.APP_NS_ID + '_route_change', [route]);
				}
			});
		},

		/**
		 * Get route
		 */
		'getRoute': function () {
			var returnValue = null;
			var hash = new String(location.hash);
			// Extract the uri without useless chars
			hash = hash.substr(0, 2) == '#!' ? hash.substr(2) : hash;

			// if the hash is not empty
			if (hash != '') {
				returnValue = $.route.deparam(hash);
			}

			return returnValue;
		}

	});
});