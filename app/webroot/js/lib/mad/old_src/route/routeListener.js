/*
 * @page mad.route Route
 * @tag mad.route
 * @parent index
 * @hide
 *
 * Route doc to make
 * 
 */

steal('can/construct').then(function () {

	/*
	 * @class mad.route.RouteListener
	 * @inherits can.Construct
	 * @parent mad.route
	 * 
	 * The route listener will be one of the stone reference of the application.
	 * It will be the guarantor of the route change
	 * 
	 */
	can.Construct('mad.route.RouteListener', /** @static */ {
	}, /** @prototype */ {
		'init': function () {
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
					if (mad.bus) {
						mad.bus.trigger(mad.APP_NS_ID + '_route_change', [route]);
					}
				}
			});
		},

		/**
		 * Get the current route based on the hash
		 * @return {mad.route.Route}
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