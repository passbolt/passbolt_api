steal(
	'app/controller/component/tagsListController.js',
	'app/controller/form/tag/editFormController.js'
).then(function () {

		/*
		 * @class passbolt.controller.tagsController
		 * @inherits mad.controller.ComponentController
		 * @parent index
		 *
		 * @constructor
		 * Creates a new Tags Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * 	- foreignModel : name of the model this controller operates on (polymorphic behavior)
		 * 	- foreignId : Id of the object this controller will perform operations on (polymorphic behavior)
		 * 	- wrapperController : a reference to the controller wrapping this object. Passing this means informing
		 * 	the wrapper when the state gets back to ready.
		 *
		 * @return {passbolt.controller.TagsController}
		 */
		mad.controller.ComponentController.extend('passbolt.controller.component.TagsController', /** @static */ {

			'defaults': {
				'label': 'Tags Controller',
				// the resource to bind the component on
				'resource'		: this.options.resource,
				'foreignModel' 	: null,
				'foreignId' 	: null,
				/**
				 * The controller that called us, if any
				 * We use it only to inform about the current mode (edit / ready)
				 */
				'wrapperController' : null,
				/**
				 * list of itemTags
				 */
				'itemTags' 		: null
			}

		}, /** @prototype */ {



			/* ************************************************************** */
			/* LISTEN TO THE MODEL EVENTS */
			/* ************************************************************** */

			'afterStart': function () {
				var self = this;

				// create a form to edit the tags and plug it onto the current resource
				this.editFormController = new passbolt.controller.form.tag.EditFormController($('.tags-edit', this.element), {
					'templateBased'	: true,
					'templateUri'	: 'app/view/template/form/tag/editForm.ejs',
					'foreignModel'	: this.options.foreignModel,
					'foreignId'		: this.options.foreignId,
					'callbacks'		: {
						'submit'	: function (data) {
							// TODO : validate
							passbolt.model.ItemTag.createBulk(data['passbolt.model.ItemTag'], function(data) {
								var itemTags = passbolt.model.ItemTag.models(data);
								self.setItemTags(itemTags);
								self.options.wrapperController.setState('ready');
							});
						}
					}
				});
				// Start the form
				this.editFormController.start();
				// Hide the comment add form by default
				this.editFormController.setState('hidden');

				// Instantiate the Tags List controller
				// It will take care of listing the tags
				this.tagsListController = new passbolt.controller.component.TagsListController($('ul.tags', this.element), {
					'resource'		: this.options.resource,
					'foreignModel'	: this.options.foreignModel,
					'foreignId'		: this.options.foreignId
				});
				// Start the Tags List Controller
				this.tagsListController.start();

				// load the tags for the given context
				passbolt.model.ItemTag.findAll({
					'foreignModel'	: this.options.foreignModel,
					'foreignId'		: this.options.foreignId
				}, function (itemTags, response, request) {
					self.setItemTags(itemTags);
				}, function (response) {
				});
			},


			/**
			 * Set ItemTags for the current controller, and inform all the other components about it
			 * @param ItemTag itemTags
			 */
			'setItemTags':function(itemTags){
				self.options.itemTags = itemTags;
				self.dispatchTags();
				if(self.options.wrapperController != null) {
					self.options.wrapperController.onChange();
				}
				self.refresh();
				// TODO : user feedback
				self.setState('ready');
			},

			/**
			 * Refresh the display depending on the context
			 */
			'refresh':function(){
				this.tagsListController.setState('ready');
				if(this.options.itemTags != null && this.options.itemTags.length > 0){
					this.editFormController.setState('hidden');
				}
				else{
					this.editFormController.setState('ready');
				}
			},

			/**
			 * reload the tags for all the connected controllers, as per itemTags
			 */
			'dispatchTags': function() {
				this.tagsListController.setTags(this.options.itemTags);
				this.editFormController.setTags(this.options.itemTags);
			},

			/**
			 * Catch event Edit
			 */
			'stateEdit': function() {
				this.tagsListController.setState('hidden');
				this.editFormController.setState('ready');
			},

			/**
			 * Catch event ready
			 */
			'stateReady': function() {
				this.refresh();
			}

		});

	});