import 'app/model/comment';
import 'app/view/component/comments';
import 'app/component/comments_list';
import 'app/form/comment/create';
import 'app/view/template/component/comments.ejs!';

/**
 * @inherits mad.Component
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
 * @return {passbolt.component.Comments}
 */
var Comments = passbolt.component.Comments = mad.Component.extend('passbolt.component.Comments', /** @static */ {

	defaults: {
		label					: 'Comments Controller',
		viewClass				: passbolt.view.component.Comments,
		// the resource to bind the component on
		resource				: null,
		foreignModel 			: null,
		foreignId 				: null,
		templateUri				: 'app/view/template/component/comments.ejs',
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function () {
		// create a form to add a comment and plug it onto the current resource
		this.addForm = new passbolt.form.comment.Create($('#js_rs_details_comments_add_form', this.element), {
			'foreignModel'	: this.options.foreignModel,
			'foreignId'		: this.options.foreignId
		});
		//this.addForm.setViewData({'resource' : this.options.resource}); // This doesn't work. why ?
		this.addForm.start();
		// Hide the comment add form by default
		this.addForm.setState('hidden');

		// Instantiate the comments List controller
		// It will take care of listing the comments
		this.commentsList = new passbolt.component.CommentsList($('#js_rs_details_comments_list', this.element), {
			'resource'		: this.options.resource,
			'foreignModel'	: this.options.foreignModel,
			'foreignId'		: this.options.foreignId
		});
		this.commentsList.start();

        // Load comments.
        // If no comments, display the add form by default.
        var self = this;
        // load the comments for the given context
        passbolt.model.Comment.findAll({
            'foreignModel'	: this.options.foreignModel,
            'foreignId'		: this.options.foreignId
        }, function (comments, response, request) {
            if (comments.length > 0) {
                // load the tree with the comments
                self.commentsList.load(comments);
            }
            else {
                self.addForm.setState('visible');
            }
        });
        this._super();
	},

	'{passbolt.model.Comment} created': function (model, ev, resource) {
		// If the new comment belongs to the displayed resource.
		if (resource.foreign_id == this.options.resource.id) {
			this.addForm.setState('hidden');
			this.commentsList.insertItem(resource, null, 'first');
		}
	},

	/**
	 * Catches event request_delete_comment, and proceed with deleting a comment
	 * @param model
	 * @param ev
	 * @param resource
	 */
	'{mad.bus.element} request_delete_comment' : function (model, ev, resource) {
		resource.destroy().then(function() {
            mad.bus.trigger('comment_deleted', resource)
		});
	},

	/**
	 * catches a comment_deleted event. (when a comment is successfully deleted)
	 * @param model
	 * @param ev
	 * @param resource
	 */
	'{mad.bus.element} comment_deleted' : function (model, ev, resource) {
		// Todo : user feedback
		// Todo : nice animation on remove
		this.commentsList.removeItem(resource);
        if (this.commentsList.options.items.attr('length') == 0) {
            this.addForm.emptyContent();
            this.addForm.setState('visible');
        }
	}
});

export default Comments;