import 'mad/view/view';
import 'mad/view/component/tree';
import 'app/view/template/component/comments.ejs!';

/*
 * @class passbolt.view.component.CommentsList
 * @inherits mad.view.component.Tree.extend
 */
var CommentsList = passbolt.view.component.CommentsList = mad.View.extend('passbolt.view.component.CommentsList', /** @static */ {

}, /** @prototype */ {

	init: function (el, options) {
		this._super(el, options);
	},

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user clicks on the delete button for comment
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @return {void}
	 */
	'.actions a .icon.delete click': function (el, ev) {
		ev.stopPropagation();
		ev.preventDefault();

		var data = null;
		var li = el.parents('li.comment-wrapper');

		if (this.getController().getItemClass()) {
			data = li.data(this.getController().getItemClass().fullName);
		} else {
			data = li[0].id;
		}

		el.trigger('request_delete_comment', data);
	}
});

export default CommentsList;