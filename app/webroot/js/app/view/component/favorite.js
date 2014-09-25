steal(
	'mad/view',
	'app/view/template/component/favorite.ejs'
).then(function () {

	/*
	 * @class passbolt.view.component.Favorite
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('passbolt.view.component.Favorite', /** @static */ {

	}, /** @prototype */ {

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * 
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		' click': function (el, ev) {
			this.element.trigger('trigger');
		}

	});
});