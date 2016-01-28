import 'mad/view/view';
import 'app/view/template/component/permissions.ejs!';


/**
 * @inherits mad.view.View
 */
var Permissions = passbolt.view.component.Permissions = mad.View.extend('passbolt.view.component.Permissions', /** @static */ { }, /** @prototype */ {

	/* ************************************************************** */
	/* LISTEN TO VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user want to delete a permission.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
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
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	' .js_share_rs_perm_type changed': function(el, ev, data) {
		ev.stopPropagation();
		ev.preventDefault();

		var li = el.parents('li'),
			permission = li.data('passbolt.model.Permission');

		this.element.trigger('request_permission_edit', [permission, data.value]);
	},

	/**
	 * Observe when the user want to reset the filter
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	' #js_perm_create_form_add_btn click': function(el, ev) {
		ev.stopPropagation();
		ev.preventDefault();

		el.trigger('submit');
	}

});
