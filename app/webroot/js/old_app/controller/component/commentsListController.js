steal(
	'mad/controller/component/treeController.js',
	'app/view/component/commentsList.js',
	'app/view/component/comments.js',
	'app/model/comment.js'
).then(function () {

		/*
		 * @class passbolt.controller.component.CommentsListController
		 * @inherits mad.controller.component.TreeController
		 * @parent index
		 *
		 * @constructor
		 * Creates a new Comments List Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * 	 - foreignModel : the model the comment list system will be plugged to
		 * 	 - foreign Id : the resource id (foreign key) the comment list system will be plugged to
		 * @return {passbolt.controller.component.CommentsListsController}
		 */
		mad.controller.component.TreeController.extend('passbolt.controller.component.CommentsListController', /** @static */ {
			'defaults': {
				'label': 'Comments List Controller',
				'viewClass': passbolt.view.component.CommentsList,
				'itemClass': passbolt.model.Comment,
				'templateUri': 'mad/view/template/component/tree.ejs',
				'itemTemplateUri': 'app/view/template/component/comment/commentItem.ejs',
				'foreignModel':null,
				'foreignId':null,
				// The map to use to make jstree working with our category model
				'map': new mad.object.Map({
					'id': 'id',
					'content': 'content',
					'modified': 'modified',
					'creatorAvatarPath': {
						'key': 'Creator',
						'func': function(creator, map, obj) {
							return creator.Profile.avatarPath('small');
						}
					},
					'creatorName': {
						'key': 'Creator',
						'func': function(creator, map, obj) {
							return creator.Profile.fullName();
						}
					}
				})
			}

		}, /** @prototype */ {

			// Constructor like
			'init': function (el, opts) {
				this._super(el, opts);
				var self = this;

				// load the comments for the given context
				passbolt.model.Comment.findAll({
					'foreignModel'	: this.options.foreignModel,
					'foreignId'		: this.options.foreignId
				}, function (comments, response, request) {
					// load the tree with the comments
					self.load(comments);
				});
			},

			/**
			 * Catches a request_delete_comment coming from an item in the list
			 * then redistribute on mad bus
			 * @param elt
			 * @param evt
			 * @param data
			 */
			' request_delete_comment': function(elt, evt, data) {
				mad.bus.trigger('request_delete_comment', data);
			}
		});
	});