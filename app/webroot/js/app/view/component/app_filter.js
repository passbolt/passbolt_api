import 'mad/view/view';
import 'app/view/template/component/app_filter.ejs!';

/**
 * @inherits mad.view.View
 */
var AppFilter = passbolt.view.component.AppFilter = mad.View.extend('passbolt.view.component.AppFilter', /** @static */ {

	defaults: {}

}, /** @prototype */ {

	/* ************************************************************** */
	/* LISTEN TO VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user update the filter
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'form submit': function(el, ev) {
		this.element.trigger('update');
	}

});
export default AppFilter;
