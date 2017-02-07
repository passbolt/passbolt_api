import 'mad/form/form';
import 'app/model/resource';
import 'app/view/template/form/resource/edit_description.ejs!';

passbolt.form.resource = passbolt.form.resource || {};

/**
 * @inherits {mad.Form}
 * @parent index
 *
 * @constructor
 * Instantiate a Edit Resource Description Form Controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.form.resource.EditDescription}
 */
var EditDescription = passbolt.form.resource.EditDescription = mad.Form.extend('passbolt.form.resource.EditDescription', /** @static */ {

	defaults : {
		templateBased : true,
		templateUri : 'app/view/template/form/resource/edit_description.ejs',
		// The current resource
		resource: null,
		// Description field.
		descriptionField: null
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * Initialize the form elements.
	 * @see {mad.Component}
	 */
	afterStart : function() {
		// id hidden field
		this.addElement(new mad.form.Textbox($('.js_resource_id', this.element), {
			modelReference : 'passbolt.model.Resource.id'
		}).start().setValue(this.options.resource.id));

		// Init the description field.
		this.options.descriptionField = this.addElement(
			new mad.form.Textbox($('.js_resource_description', this.element), {
				modelReference : 'passbolt.model.Resource.description'
			}).start(),
			new mad.form.Feedback($('.js_resource_description_feedback', this.element), {}).start()
		);

		// Update the resource description with current value
		this.options.descriptionField.setValue(this.options.resource.description);

		// Force event submit event (not thrown by default)
		// TODO : understand why we need to do that... weird
		$('.button.resource-submit').click(function(){
			$(this).trigger('submit');
		});
	},

    /**
     * Reset description in description field.
     * @param description
     */
    reset : function(description) {
        this._super();
        if (description == undefined) {
            description = this.options.resource.description;
        }
        this.options.descriptionField.setValue(description);
    }
});

export default EditDescription;