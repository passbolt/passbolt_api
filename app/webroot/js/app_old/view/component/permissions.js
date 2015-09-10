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
		 * Observe when the user want to delete a permission.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		' .js_perm_delete click': function(el, ev) {
			ev.stopPropagation();
			ev.preventDefault();

			var li = el.parents('li');
			var permission = li.data('passbolt.model.Permission');
			this.element.trigger('request_permission_delete', [permission]);
		},

		/**
		 * Observe when the user want to edit a permission type.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'.js_share_rs_perm_type changed': function(el, ev, data) {
			ev.stopPropagation();
			ev.preventDefault();

			var li = el.parents('li'),
				permission = li.data('passbolt.model.Permission');

			this.element.trigger('request_permission_edit', [permission, data.value]);
		},

		/**
		 * Observe when the user want to reset the filter
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'#js_perm_create_form_add_btn click': function(el, ev) {
			ev.stopPropagation();
			ev.preventDefault();

			el.trigger('submit');
		}

	});
});
