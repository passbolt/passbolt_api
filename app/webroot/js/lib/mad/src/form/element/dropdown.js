import 'mad/form/choice_element';
import 'mad/view/form/element/dropdown';
import 'mad/view/template/form/dropdown.ejs!';

/**
 * @parent Mad.form_api
 * @inherits mad.Component
 *
 * The Dropdown Form Element
 * @todo TBD
 */
var Dropdown = mad.form.Dropdown = mad.form.ChoiceElement.extend('mad.form.Dropdown', /* @static */ {

    defaults: {
        // Override the label option.
        label: 'DropDown Form Element',
        // Override the tag option.
        tag: 'div',
        // Override the templateUri option.
        templateUri: 'mad/view/template/form/dropdown.ejs',
        // Override the templateBased option.
        templateBased: true,
        // Override the viewClass option.
        viewClass: mad.view.form.DropDown,
        // Allow empty value.
        emptyValue: true

    }

}, /** @prototype */ {});

export default Dropdown;
