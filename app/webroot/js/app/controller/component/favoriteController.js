steal(
    'mad/controller/component/buttonController.js',
	'app/view/component/favorite.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.FavoriteController
	 * @inherits {mad.controller.component.ComponentController}
	 * @parent index
	 * 
	 * 
	 * @constructor
	 * Instanciate a favorite controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.CopyLoginButtonController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.FavoriteController', /** @static */ {

		'defaults': {
			'label': 'Favorite',
			'viewClass': passbolt.view.component.Favorite,
			// The associated model instance browser
			'instance': null
		}

	}, /** @prototype */ {

		'init': function(el, options) {
			this._super(el, options);
			this.setViewData('instance', this.options.instance);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the mouse leave the component
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'click': function (el, ev) {
			ev.stopPropagation();
			ev.preventDefault();
			
			if (!this.options.instance.isFavorite()) {
				mad.bus.trigger('request_favorite', this.options.instance);
			} else {
				mad.bus.trigger('request_unfavorite', this.options.instance);
			}
		}


		/* ************************************************************** */
		/* LISTEN TO THE MODEL EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when an resource is updated
		 * @param {passbolt.model.Resource} resource The updated resource
		 * @return {void}
		 */
		// '{passbolt.model.Resource} updated': function (model, ev, resource) {
		// // '{instance} updated': function (el, ev) { // @todo @update not working for now, check after canjs update
			// if (this.options.instance.id == resource.id) {
				// console.log('update the favorite icon');
			// }
		// }

	});
});
