import 'mad/view/view';
import 'app/view/template/component/favorite.ejs!';


/**
 * @inherits mad.view.View
 */
var Favorite = passbolt.view.component.Favorite = mad.View.extend('passbolt.view.component.Favorite', /** @static */ {

}, /** @prototype */ {

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 *
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	' click': function (el, ev) {
		this.element.trigger('trigger');
	}

});