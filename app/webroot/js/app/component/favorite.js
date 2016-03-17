import 'mad/component/button';
import 'app/view/component/favorite';

import 'app/view/template/component/favorite.ejs!';

/**
 * @inherits {mad.Component}
 * @parent index
 *
 *
 * @constructor
 * Instantiate a favorite controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.Favorite}
 */
var Favorite = passbolt.component.Favorite = mad.Component.extend('passbolt.component.Favorite', /** @static */ {

	defaults: {
		label: 'Favorite',
		viewClass: passbolt.view.component.Favorite,
		// The associated model instance browser
		instance: null,
        templateUri: 'app/view/template/component/favorite.ejs'
	}

}, /** @prototype */ {

	init: function (el, options) {
		this._super(el, options);
		this.setViewData('instance', this.options.instance);
	},

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the mouse leave the component
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	click: function (el, ev) {
		var self = this;

		// Block the default behavior and don't propagate the event to avoid the grid controller
		// to catch it and select/unselect the row behind this component.
		ev.preventDefault();
		ev.stopPropagation();

		// If the component is already requesting a change, drop this request.
		if (this.state.is('loading')) {
			return;
		}

		// Change the state of the component to lock it.
		this.setState('loading');

		// If the instance is not already a favorite.
		if (!this.options.instance.isFavorite()) {
			// Mark as a favorite.
			this.view.favorite();
			mad.bus.triggerRequest('request_favorite', this.options.instance)
				.then(function () {
					// It is not required to change the component state as it has been destroyed
					// by the grid controller when it updates the resource row.
				})
				.fail(function () {
					// Restore the state of the component.
					// Don't show any error notification as it is managed by the ajax layer for now.
					// @todo PASSBOLT-984 The component should manage its failure.
					self.setState('ready');
				});
		} else {
			// Unmark as a favorite.
			this.view.unfavorite();
			mad.bus.triggerRequest('request_unfavorite', this.options.instance)
				.then(function () {
					// It is not required to change the component state as it has been destroyed
					// by the grid controller when it updates the resource row.
				})
				.fail(function (jqXHR, status, response, request) {
					// Restore the state of the component.
					// Don't show any error notification as it is managed by the ajax layer for now.
					// @todo PASSBOLT-984 The component should manage its failure.
					self.setState('ready');
				});
		}
	}

});

export default Favorite;
