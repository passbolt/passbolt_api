steal(
	'mad/form/formElement.js'
//	'mad/view/form/element/textboxView.js'
).then(function () {

	/*
	 * @class mad.form.element.ListController
	 * @inherits mad.form.FormElement
	 * @parent mad.form.element
	 * 
	 * @constructor
	 * Creates a new List Form Element Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.form.element.ListController}
	 */
	mad.form.FormElement.extend('mad.form.element.ListController', /** @static */ {

		'defaults': {
			'label': 'List Form Element Controller',
			'templateBased': false,
			'itemClass': passbolt.model,
			'itemTemplateUri': 'app/view/template/component/tagFilterItem.ejs',
			'tag': 'ul',
			'value' : []
		}

	}, /** @prototype */ {
		
		/**
		 * after start hook.
		 */
		'afterStart': function() {
			// We use a tree to represent the list of tags ;) easy
			this.treeCtl = new mad.controller.component.TreeController(this.element, {
				'itemClass': this.options.itemClass,
				'itemTemplateUri': this.options.itemTemplateUri,
				'map': new mad.object.Map({
					'id': 'id',
					'label': 'name'
				})
			});
			this.treeCtl.start();
		},

		/**
		 * Insert new tags into the filter
		 * @param {array} tags The tags to insert to the tree. They have to belong 
		 * to the model class defined by the options.itemClass variable
		 * @return {void}
		 */
		'setValue': function (tags) {
			var self = this;
			this.value = tags;
			this.treeCtl.reset();
			can.each(tags, function (tag, i) {
				if (!(tag instanceof self.options.itemClass)) {
					throw new mad.error.WrongParametersException('tag', self.options.itemClass.fullName);
				}
				self.treeCtl.insertItem(tag);
			});
		}
	});

});