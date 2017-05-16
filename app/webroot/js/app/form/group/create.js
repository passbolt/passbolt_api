import 'mad/form/form';
import 'mad/form/element/textbox';
import 'app/model/group';
import 'app/view/template/form/group/create.ejs!';

// Define namespace.
passbolt.form.group = passbolt.form.group ? passbolt.form.group : {};

/**
 * @class passbolt.form.group.CreateForm
 * @inherits {mad.Form}
 * @parent index
 *
 * @constructor
 * Instanciate a Group Create Form Component
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.form.group.Create}
 */
var CreateForm = passbolt.form.group.Create = mad.Form.extend('passbolt.form.group.Create', /** @static */ {

    defaults: {
        templateBased: true,
        action: 'create',
        templateUri: 'app/view/template/form/group/create.ejs',
        cssClasses: ['group_edit_form'],
        canUpdateName: true
    }

}, /** @prototype */ {

    /**
     * After start hook.
     * Initialize the form elements.
     * @see {mad.Component}
     */
    afterStart: function () {
        // Add user first name field.
        var nameField = this.addElement(
            new mad.form.Textbox($('#js_field_name'), {
                modelReference: 'passbolt.model.Group.name'
            }).start(),
            new mad.form.Feedback($('#js_field_name_feedback'), {}).start()
        );

        // Disable name field if the user is not allowed to update it.
        if (this.options.canUpdateName == false) {
            $('#js_field_name').attr('disabled', 'disabled');
        }

        // Rebind controller events
        this.on();
    }


    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

});

export default CreateForm;
