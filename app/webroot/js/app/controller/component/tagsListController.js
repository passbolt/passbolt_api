steal(
	'app/model/itemTag.js',
	'mad/controller/component/treeController.js'
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
		mad.controller.component.TreeController.extend('passbolt.controller.component.TagsListController', /** @static */ {
			'defaults': {
				'label': 'Tags List Controller',
				'viewClass': mad.view.component.tree.List,
				'itemClass': passbolt.model.ItemTag,
				//'templateUri': 'mad/view/template/component/tree.ejs',
				'itemTemplateUri': 'app/view/template/component/tag/tagItem.ejs',
				'foreignModel':null,
				'foreignId':null,
				// The map to use to make jstree working with our category model
				'map': new mad.object.Map({
					'id': 'id',
					'foreign_id': 'foreign_id',
					'foreign_model': 'foreign_model',
					'created_by': 'created_by',
					'created': 'created',
					'Tag':'Tag'
				})
			}

		}, /** @prototype */ {

			// Constructor like
			'init': function (el, opts) {
				this._super(el, opts);
				var self = this;
				//this.setState('loading');

				// load the comments for the given context
				passbolt.model.ItemTag.findAll({
					'foreignModel'	: this.options.foreignModel,
					'foreignId'		: this.options.foreignId
				}, function (itemTags, response, request) {
					// load the tree with the comments
					self.load(itemTags);
					//self.setState('ready');
				}, function (response) {
				});
			}

		});
	});