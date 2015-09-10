steal(
	'mad/view',
	'app/view/template/component/appFilter.ejs'
).then(function () {

	/*
	 * @class passbolt.view.component.AppFilter
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('passbolt.view.component.AppFilter', /** @static */ {

		'defaults': {}

	}, /** @prototype */ {

		/* ************************************************************** */
		/* LISTEN TO VIEW EVENTS */
		/* ************************************************************** */
		
		/**
		 * Observe when the user update the filter
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'form submit': function(el, ev) {
			this.element.trigger('update');
		}
		
	});
});
