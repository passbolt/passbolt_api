import 'mad/view/view';
import 'app/view/template/component/comments.ejs!';

/**
 * @inherits mad.view.View
 */
var Comments = passbolt.view.component.Comments = mad.View.extend('passbolt.view.component.Comments', /** @static */ {

}, /** @prototype */ {

	init: function (el, options) {
		this._super(el, options);
	},

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */


	/**
	 * Observe when the user clicks on the plus button, to add a comment
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	' a.js_add_comment click': function (el, ev) {
		// Displays the add comment form
		this.getController().addForm.setState('ready');
	},

	/**
	 * Observe when the user clicks on submit to save the comment
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'a.button.comment-submit click': function (el, ev) {
		el.trigger('submit');
	}
});

export default Comments;