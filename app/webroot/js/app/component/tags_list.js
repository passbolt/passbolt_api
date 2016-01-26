import 'app/model/item_tag';
import 'mad/component/tree';
import 'app/view/template/component/tag/tag_item.ejs!';

/**
 * @inherits mad.component.Tree
 * @parent index
 *
 * @constructor
 * Creates a new Tags List Controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.TagsList}
 */
var TagsList = passbolt.component.TagsList = mad.Component.extend('passbolt.component.TagsList', /** @static */ {
	'defaults': {
		label: 'Tags List Component',
		viewClass: mad.view.component.tree.List,
		itemClass: passbolt.model.ItemTag,
		itemTemplateUri: 'app/view/template/component/tag/tag_item.ejs',
		// The list of tags to take care
		tags: null,
		// The map to use to make jstree working with our category model
		map: new mad.Map({
			id: 'id',
			Tag:'Tag'
		})
	}

}, /** @prototype */ {

	/**
	 * after start
	 */
	afterStart: function() {
		if(this.options.tags != null) {
			this.load(this.options.tags);
		}
	},

	/* ************************************************************** */
	/* LISTEN TO THE MODEL EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when item tags are added to the observed instance
	 * @param {mad.model.Model} model The model reference
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {passbolt.model.ItemTag} itemTags The added item tags
	 */
	'{tags} add': function (model, ev, itemTags) {
		var self = this;
		for(var i in itemTags) {
			self.insertItem(itemTags[i]);
		};
	},

	/**
	 * Observe when item tags are removed to the observed instance
	 * @param {mad.model.Model} model The model reference
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {passbolt.model.ItemTag} itemTags The removed item tags
	 */
	'{tags} remove': function (model, ev, itemTags) {
		var self = this;
		for(var i in itemTags) {
			self.removeItem(itemTags[i]);
		};
	}

});

export default TagsList;