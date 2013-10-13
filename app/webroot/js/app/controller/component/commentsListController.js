steal(
		'mad/controller/component/treeController.js',
    'app/view/component/comments.js',
		'app/model/comment.js'
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
         * @return {passbolt.controller.CommentsController}
         */
				mad.controller.component.TreeController.extend('passbolt.controller.component.CommentsListController', /** @static */ {
            'defaults': {
                'label': 'Comments List Controller',
								'viewClass': mad.view.component.tree.List,
								'itemClass': passbolt.model.Comment,
								//'templateUri': 'mad/view/template/component/tree.ejs',
								'itemTemplateUri' : 'app/view/template/component/comment/commentItem.ejs',
								// The map to use to make jstree working with our category model
								'map': new mad.object.Map({
									'id': 'id',
									'content': 'content',
									'modified': 'modified',
									'created_by': 'created_by'
									/*'children': {
										'key': 'children',
										'func': mad.object.Map.mapObjects
									}*/
								})
            }

        }, /** @prototype */ {
					
					// Constructor like
					'init': function(el, opts) {
						this._super(el, opts);
						var self = this;
						// load categories function of the selected database
						//this.setState('loading');

						passbolt.model.Comment.findAll({
							'id': this.options.resource.id
						}, function (comments, response, request) {
							// load the tree with the comments
							self.load(comments);
							//self.setState('ready');
						}, function (response) { });
					}
					
        });

    });