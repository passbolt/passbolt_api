steal(
	'app/controller/component/tagsListController.js',
	'app/controller/form/tag/editFormController.js'
).then(function () {

		/*
		 * @class passbolt.controller.SidebarSectionTagsController
		 * @inherits mad.controller.component.SidebarSectionController
		 * @parent index
		 *
		 * @constructor
		 * Creates a new Sidebar Section Tags Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.SidebarSectionTagsController}
		 */
		mad.controller.ComponentController.extend('passbolt.controller.component.TagsController', /** @static */ {

			'defaults': {
				'label': 'Tags Controller',
				//'viewClass': passbolt.view.component.SidebarSection.SidebarSectionTags,
				// the resource to bind the component on
				'resource'		: this.options.resource,
				'foreignModel' 	: null,
				'foreignId' 	: null
			}

		}, /** @prototype */ {



			/* ************************************************************** */
			/* LISTEN TO THE MODEL EVENTS */
			/* ************************************************************** */

			'afterStart': function () {
				var self = this;

				// create a form to add a comment and plug it onto the current resource
				this.editFormController = new passbolt.controller.form.tag.EditFormController($('.tags-edit', this.element), {
					'templateBased'	: true,
					'templateUri'	: 'app/view/template/form/tag/editForm.ejs',
					'foreignModel'	: this.options.foreignModel,
					'foreignId'		: this.options.foreignId,
					'callbacks'		: {
						'submit'	: function (data) {
							// TODO : validate
							passbolt.model.ItemTag.createBulk(data['passbolt.model.ItemTag'], function(data) {
								self.itemTags = data;
								self.reloadTags();
							});
						}
					}
				});
				//this.addFormController.setViewData({'resource' : this.options.resource}); // This doesn't work. why ?
				this.editFormController.start();
				// Hide the comment add form by default
				//this.editFormController.setState('hidden');

				// Instantiate the comments List controller
				// It will take care of listing the comments
				this.tagsListController = new passbolt.controller.component.TagsListController($('ul.tags', this.element), {
					'resource'		: this.options.resource,
					'foreignModel'	: this.options.foreignModel,
					'foreignId'		: this.options.foreignId
				});
				this.tagsListController.start();

				// load the tags for the given context
				passbolt.model.ItemTag.findAll({
					'foreignModel'	: this.options.foreignModel,
					'foreignId'		: this.options.foreignId
				}, function (itemTags, response, request) {
					// load the tree with the comments
					self.itemTags = itemTags;
					self.reloadTags();
					//self.load(itemTags);
					//self.setState('ready');
				}, function (response) {
				});
			},

			'reloadTags': function() {
				this.tagsListController.setTags(this.itemTags);
				this.editFormController.setTags(this.itemTags);
			},

            'editMode': function(state) {
                this.tagsListController.setState('hidden');
            }

		});

	});