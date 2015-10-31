steal(
	'mad/form/formElement.js'
).then(function () {

	/*
	 * @class mad.form.element.FreeElementController
	 * @inherits mad.form.FormElement
	 * @parent mad.form.element
	 * 
	 * Our implementation of what a free form element could be
	 * 
	 * @constructor
	 * Creates a new Free Form Element Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.form.element.TextboxController}
	 */
	mad.form.FormElement.extend('mad.form.element.FreeElementController', /** @static */ {

		'defaults': {
			'label': 'free Form Element Controller',
			'tag': 'input',
			// @todo, not used this is just an idea about how we could manage DOM element attributes, should
			// be better in a view, like the tag, but should be heavy to create a file just for that. We 
			// should thing maybe to put that in view scope { tag: ... , attributes: ... } 
			'attributes': {
				'type': 'hidden'
			}
		}

	}, /** @prototype */ {
	/**
		 * Set the value of the form element
		 * @param {mixed} value The value to set
		 * @return {mad.form.FormElement}
		 */
		'setValue': function (value) {
			this.value = value;
			return this;
		}
		
	});

});