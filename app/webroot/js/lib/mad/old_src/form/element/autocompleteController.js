// NOT USED : User by the share component, this one will be migrated to the plugin.
// Doesn't need to be migrated.

steal(
	'mad/form/element/textboxController.js',
	'mad/view/form/element/autocompleteView.js'
).then(function () {

	/*
	 * @class mad.form.element.AutocompleteController
	 * @inherits mad.form.element.TextboxController
	 * @parent mad.form.element
	 * 
	 * The Autocomplete Form Element Controller is our implementation of the UI component autocomplete textbox
	 * 
	 * @constructor
	 * Creates a new Autocomplete Textbox Form Element Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.form.element.AutocompleteController}
	 */
	mad.form.element.TextboxController.extend('mad.form.element.AutocompleteController', /** @static */ {

		'defaults': {
			'label': 'Autocomplete Form Element Controller',
			'viewClass': mad.view.form.element.AutocompleteView,
			'tag': 'input',
			'list': null
		}

	}, /** @prototype */ {
		
		'afterStart' : function() {
			// Add an list component
			// This list will allow the autocomplete component to display the available choices
			var listOpts = {
				'viewClass': mad.view.component.tree.List,
				'itemClass': mad.model.Model,
				'cssClasses': ['autocomplete-content'],
				'templateUri': 'mad/view/template/component/tree.ejs',
				'state': 'hidden',
				// The map to use to make jstree working with our category model
				'map': new mad.object.Map({
					'id': 'id',
					'label': 'label',
					'model': 'model'
				})
			};
			this.options.list = mad.helper.ComponentHelper.create(this.element, 'after', mad.controller.component.TreeController, listOpts);
			this.options.list.start();
			this.on();
		},

		/**
		 * The user want to remove a permission
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Permission} permission The permission to remove
		 * @return {void}
		 */
		' changed': function(el, ev, data) {
			var self = this;
			// autocomplete the given string
			// By using the given callback
			if(this.options.callbacks.ajax) {
				this.options.callbacks.ajax.apply(this, [data.value])
					.done(function(ajaxData) {
						self.options.list.reset();
						self.options.list.load(ajaxData);
						self.options.list.setState('ready');
					});
			}
		},

		/**
		 * An item has been selected in the autocomplete list
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mad.model.Model} instance The selected instance
		 * @return {void}
		 */
		'{list} item_selected': function(el, ev, data) {
			// update the value of the autocomplete field with the selected value
			this.setValue(data.label);
			// hide the autocomplete list
			this.options.list.setState('hidden');
			// Trigger the event on the main component.
			this.element.trigger('item_selected', [data, ev]);
		}

	});

});
