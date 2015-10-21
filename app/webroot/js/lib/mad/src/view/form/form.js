import 'mad/view/view';

// Initialize the view form namespaces.
mad.view.form = mad.view.form || {};

/**
 * @inherits mad.View
 */
var Form = mad.view.Form = mad.View.extend('mad.view.Form', /* @static */ {}, /** @prototype */ {

    /**
     * Set the state of an embedded element.
     *
     * @param element
     * @param state
     */
    setElementState: function (element, state) {
        // Element's id.
        var eltId = element.getId(),
            $label = $('label[for="' + eltId + '"]'),
            $wrapper = element.element.parent('.js_form_element_wrapper');

        switch (state) {
            case 'success':
                if ($label) {
                    $label.removeClass('error');
                }
                if ($wrapper) {
                    $wrapper.removeClass('error');
                }
                break;
            case 'error':
                if ($label) {
                    $label.addClass('error');
                }
                if ($wrapper) {
                    $wrapper.addClass('error');
                }
                break;
        }
    }
});

export default Form;
