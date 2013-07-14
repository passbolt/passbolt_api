steal(
	'mad/view',
	'app/view/template/component/permissions.ejs'
).then(function () {

	/*
	 * @class passbolt.view.component.AppFilter
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('passbolt.view.component.Permissions', /** @static */ {

		'defaults': {}

	}, /** @prototype */ {

		/* ************************************************************** */
		/* LISTEN TO VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the user want to reset the filter
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'.js_delete click': function(el, ev) {
			var li = el.parents('li');
			var permission = li.data('passbolt.model.Permission');
			this.element.trigger('delete', [permission]);
		},
		
		/**
		 * Observe when the user want to reset the filter
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'#js_permission_add_form submit': function(el, ev) {
			this.element.trigger('add_permission');
		}
		
	});
});