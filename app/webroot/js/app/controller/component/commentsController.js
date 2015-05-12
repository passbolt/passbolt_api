steal(
	'app/model/comment.js',
	'app/view/component/comments.js',
	'app/controller/component/commentsListController.js',
	'app/controller/form/comment/createFormController.js'
).then(function () {

	/*
	 * @class passbolt.controller.CommentsController
	 * @inherits mad.controller.component.ComponentController
	 * @parent index
	 *
	 * @constructor
	 * Creates a new Comments controller
	 *
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * 	 - foreignModel : the model the comment system will be plugged to
	 * 	 - foreign Id : the resource id (foreign key) the comment system will be plugged to
	 * @return {passbolt.controller.CommentsController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.CommentsController', /** @static */ {
		'defaults': {
			'label'			: 'Comments Controller',
			'viewClass'		: passbolt.view.component.Comments,
			// the resource to bind the component on
			'resource'		: this.options.resource,
			'foreignModel' 	: null,
			'foreignId' 	: null,

			'commentsListController' : null
		}

	}, /** @prototype */ {

		/**
		 * Called right after the start function
		 * @return {void}
		 * @see {mad.controller.ComponentController}
		 */
		'afterStart': function () {
			// create a form to add a comment and plug it onto the current resource
			this.addFormController = new passbolt.controller.form.comment.CreateFormController($('#js_rs_details_comments_add_form', this.element), {
				'templateBased'	: true,
				'templateUri'	: 'app/view/template/form/comment/addForm.ejs',
				'foreignModel'	: this.options.foreignModel,
				'foreignId'		: this.options.foreignId
			});
			//this.addFormController.setViewData({'resource' : this.options.resource}); // This doesn't work. why ?
			this.addFormController.start();
			// Hide the comment add form by default
			this.addFormController.setState('hidden');

			// Instantiate the comments List controller
			// It will take care of listing the comments
			this.commentsListController = new passbolt.controller.component.CommentsListController($('#js_rs_details_comments_list', this.element), {
				'resource'		: this.options.resource,
				'foreignModel'	: this.options.foreignModel,
				'foreignId'		: this.options.foreignId
			});
			this.commentsListController.start();
		},

		'{passbolt.model.Comment} created': function (model, ev, resource) {
			var self = this;
			// If the new resource belongs to one of the categories displayed by the resource
			if (resource.foreign_id == this.options.resource.id) {
				self.commentsListController.insertItem(resource, null, 'first');
				self.addFormController.setState('hidden');
				return false; // break
			}
		},

		/**
		 * Catches event request_delete_comment, and proceed with deleting a comment
		 * @param model
		 * @param ev
		 * @param resource
		 */
		'{mad.bus} request_delete_comment' : function (model, ev, resource) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			resource.destroy(function(){
				mad.bus.trigger('comment_deleted', resource);
			});
		},

		/**
		 * catches a comment_deleted event. (when a comment is successfully deleted)
		 * @param model
		 * @param ev
		 * @param resource
		 */
		'{mad.bus} comment_deleted' : function (model, ev, resource) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			// Todo : user feedback
			// Todo : nice animation on remove
			this.commentsListController.removeItem(resource);
		}

	});
});