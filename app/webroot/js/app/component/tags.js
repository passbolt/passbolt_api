import 'app/component/tags_list';
//import 'app/form/tag/edit';
import 'app/view/template/component/tags.ejs!';


/**
 * @inherits mad.controller.ComponentController
 * @parent index
 *
 * @constructor
 * Creates a new Tags Component
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * 	- foreignModel : name of the model this controller operates on (polymorphic behavior)
 * 	- foreignId : Id of the object this controller will perform operations on (polymorphic behavior)
 * 	- wrapperController : a reference to the controller wrapping this object. Passing this means informing
 * 	the wrapper when the state gets back to ready.
 *
 * @return {passbolt.component.Tags}
 */
var Tags = passbolt.component.Tags = mad.Component.extend('passbolt.component.Tags', /** @static */ {

	defaults: {
		label: 'Tags Component',
		// the model instance to bind the component on
		instance: null,
		// Name of the model this controller operates on (polymorphic behavior)
		foreignModel: null,
		// Id of the object this controller will perform operations on (polymorphic behavior)
		foreignId: null
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function () {
		var self = this;

		// create a form to edit the tags and plug it onto the current resource
		this.editForm = new passbolt.form.tag.Edit($('.tags-edit', this.element), {
			'templateBased': true,
			'templateUri': 'app/view/template/form/tag/edit.ejs',
			'foreignModel': this.options.foreignModel,
			'foreignId': this.options.foreignId,
			'tags': this.options.instance.ItemTag,
			'data': {
				'ItemTag': this.options.instance.ItemTag
			},
			'callbacks': {
				'submit': function (data) {
					// TODO : validate
					passbolt.model.ItemTag.createBulk(data['passbolt.model.ItemTag'], function(data) {
						var itemTags = passbolt.model.ItemTag.models(data);
						self.refreshInstanceTags(itemTags);
					});
				}
			}
		}).start();

		// Instantiate the Tags List controller
		// It will take care of listing the tags
		this.tagsList = new passbolt.component.TagsList($('ul.tags', this.element), {
			'tags': this.options.instance.ItemTag
		}).start();

		// if the instance's item tags have not been loaded
		// load the tags for the given context
		passbolt.model.ItemTag.findAll({
			'foreignModel': this.options.foreignModel,
			'foreignId': this.options.foreignId
		}, function (itemTags, response, request) {
			self.refreshInstanceTags(itemTags);
		}, function (response) {
		});
	},

	/**
	 * Refresh instance tags
	 * @param {passbolt.model.ItemTag.models} itemTags Collections of Item tags which will replace the existing instance's item tags
	 */
	refreshInstanceTags: function(itemTags) {
		// Destroy all the existing tags from the tagged instance (Behavior of the Server)
		this.options.instance.ItemTag.splice(0);
		// Add all the new item tags to the tagged instance
		this.options.instance.ItemTag.push.apply(this.options.instance.ItemTag, itemTags);
	},

	/* ************************************************************** */
	/* LISTEN TO THE MODEL EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when something happened onto the instance's item tags list
	 * @param {mad.model.Model} model The model reference
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {passbolt.model.ItemTag} itemTags The added item tags
	 */
	'{instance.ItemTag} change': function(model, ev, itemTags) {
		// If the instance is not tagged yet,
		if(this.options.instance.ItemTag != null && this.options.instance.ItemTag.length > 0){
			this.setState('ready');
		} else {
			this.setState('edit');
		}
	},

	/* ************************************************************** */
	/* LISTEN TO STATE CHANGES */
	/* ************************************************************** */

	/**
	 * Switch the tags component controllers to the edit mode
	 * @param {boolean} go Go or leave the state
	 */
	'stateEdit': function(go) {
		if (go) {
			var canUpdate = passbolt.model.Permission.isAllowedTo(this.options.instance, passbolt.UPDATE);
			// If the use is not allowed to update the associated instance.
			// He cannot edit its tags.
			if (!canUpdate) {
				return this.setState('ready');
			}

			this.tagsList.setState('hidden');
			this.editForm.setState('ready');
		}
	},

	/**
	 * Switch the tags component controllers to the initial mode
	 * @param {boolean} go Go or leave the state
	 */
	'stateReady': function(go) {
		if (go) {
			this.tagsList.setState('ready');
			this.editForm.setState('hidden');
		}
	}

});
