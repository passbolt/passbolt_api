steal(
	'mad/view',
	'mad/view/component/tree.js',
	'app/view/template/component/comments.ejs'
).then(function () {

		/*
		 * @class passbolt.view.component.Comments
		 * @inherits mad.view.View
		 */
		mad.view.component.Tree.extend('passbolt.view.component.CommentsList', /** @static */ {

		}, /** @prototype */ {

			'init': function (el, options) {
				this._super(el, options);
				console.log("init comments List");
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
				console.log('commentListView : delete intercepted');
				el.trigger('request_delete_comment');
			}
		});
	});