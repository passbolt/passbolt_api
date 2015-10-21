/**
 * @add can.event.special
 */
steal('jquery', 'can/util', function ($, can) {
	/**
	 * @property {Event} destroyed
	 * @parent specialevents
	 * @download  http://jmvcsite.heroku.com/pluginify?plugins[]=jquery/dom/destroyed/destroyed.js
	 * @test jquery/event/destroyed/qunit.html
	 * Provides a destroyed event on an element.
	 * <p>
	 * The destroyed event is called when the element
	 * is removed as a result of jQuery DOM manipulators like remove, html,
	 * replaceWith, etc. Destroyed events do not bubble, so make sure you don't use live or delegate with destroyed
	 * events.
	 * </p>
	 *
	 * <h2>Quick Example</h2>
	 * ```
	 * can.$(".foo").bind("destroyed", function(){
	 *    //clean up code
	 * })
	 * ```
	 *
	 * <h2>Quick Demo</h2>
	 * @demo jquery/event/destroyed/destroyed.html
	 * <h2>More Involved Demo</h2>
	 * @demo jquery/event/destroyed/destroyed_menu.html
	 */

	var oldClean = $.cleanData;

	can.cleanData = function (elems) {
		can.each(elems, function (elem) {
			can.$(elem)
				.triggerHandler("removed");
		});
		oldClean(elems);
	};

	return can;
});
