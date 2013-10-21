steal(
	'app/model/itemTag.js',
	'mad/controller/component/treeController.js'
).then(function () {

		/*
		 * @class passbolt.controller.component.TagsListController
		 * @inherits mad.controller.component.TreeController
		 * @parent index
		 *
		 * @constructor
		 * Creates a new Tags List Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * 	 - foreignModel : the model the tag list system will be plugged to
		 * 	 - foreign Id : the resource id (foreign key) the  tag list system will be plugged to
		 * @return {passbolt.controller.component.TagsListsController}
		 */
		mad.controller.component.TreeController.extend('passbolt.controller.component.TagsListController', /** @static */ {
			'defaults': {
				'label': 'Tags List Controller',
				'viewClass': mad.view.component.tree.List,
				'itemClass': passbolt.model.ItemTag,
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
			},

			/**
			 * Set the tags to the list
			 * @param tags
			 */
			'setTags': function(tags) {
				this.reset();
				this.load(tags);
			}
		});
	});