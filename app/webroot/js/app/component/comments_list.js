import 'mad/component/confirm';
import 'mad/component/tree';
import 'app/view/component/comments_list';
import 'app/view/component/comments';
import 'app/model/comment';
import 'app/view/template/component/comment/comment_item.ejs!';

/**
 * @inherits mad.component.Tree
 * @parent index
 *
 * @constructor
 * Creates a new Comments List
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * 	 - foreignModel : the model the comment list system will be plugged to
 * 	 - foreign Id : the resource id (foreign key) the comment list system will be plugged to
 * @return {passbolt.component.CommentsList}
 */
var CommentsList = passbolt.component.CommentsList = mad.component.Tree.extend('passbolt.component.CommentsList', /** @static */ {
	defaults: {
		label: 'Comments List Controller',
		viewClass: passbolt.view.component.CommentsList,
		itemClass: passbolt.model.Comment,
		templateUri: 'mad/view/template/component/tree.ejs',
		itemTemplateUri: 'app/view/template/component/comment/comment_item.ejs',
		foreignModel:null,
		foreignId:null,
        selfLoad:false,
		// The map to use to make jstree working with our comment model
		map: new mad.Map({
			id: 'id',
			content: 'content',
			modified: 'modified',
			creatorAvatarPath: {
				key: 'Creator',
				func: function(creator, map, obj) {
					return creator.Profile.avatarPath('small');
				}
			},
			creatorName: {
				key: 'Creator',
				func: function(creator, map, obj) {
					return creator.Profile.fullName();
				}
			}
		})
	}

}, /** @prototype */ {

	// Constructor like
	init: function (el, opts) {
		this._super(el, opts);
        if(this.options.selfLoad === true) {
            var self = this;
            // load the comments for the given context
            passbolt.model.Comment.findAll({
                foreignModel: this.options.foreignModel,
                foreignId: this.options.foreignId
            }, function (comments, response, request) {
                // load the tree with the comments
                self.load(comments);
            });
        }
	},

    /**
     * Override insertItem.
     * @param item
     * @param refItem
     * @param position
     */
    insertItem: function (item, refItem, position) {
        this._super(item, refItem, position);
        // Unhide delete action if user is owner.
        var isOwner = item.created_by != undefined && item.created_by == passbolt.model.User.getCurrent().id;
        if (isOwner) {
            var $deleteActionEl = $('li#' + item.id + ' .js_delete_comment', this.element);
            $deleteActionEl.removeClass('hidden');
        }

    },

	/**
	 * Catches a request_delete_comment coming from an item in the list
	 * then redistribute on mad bus
	 * @param elt
	 * @param evt
	 * @param data
	 */
	' request_delete_comment': function(elt, evt, data) {
        var confirm = new mad.component.Confirm(
            null,
            {
                label: __('Do you really want to delete comment ?'),
                content: __('Please confirm you really want to delete the comment. After clicking ok, the comment will be <strong>deleted permanently</strong>.'),
				submitButton: {
					label: __('delete comment'),
					cssClasses: ['warning']
				},
                action: function() {
                    mad.bus.trigger('request_delete_comment', data);
                }
            }
        ).start();
	}
});

export default CommentsList;
