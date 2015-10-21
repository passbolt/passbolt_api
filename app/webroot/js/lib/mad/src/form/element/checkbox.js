import 'mad/form/choice_element';
import 'mad/view/form/element/checkbox';
import 'mad/view/template/form/checkbox.ejs!';

/**
 * @parent Mad.form_api
 * @inherits mad.Component
 *
 * The Checkbox Form Element
 * @todo TBD
 */
var Checkbox = mad.form.Checkbox = mad.form.ChoiceElement.extend('mad.form.Checkbox', /* @static */ {

    defaults: {
        // Override the label option.
        label: 'Checkbox Form Element',
        // Override the tag option.
        tag: 'div',
        // Override the templateUri option.
        templateUri: 'mad/view/template/form/checkbox.ejs',
        // Override the templateBased option.
        templateBased: true,
        // Override the viewClass option.
        viewClass: mad.view.form.Checkbox
    }

}, /** @prototype */ {});

export default Checkbox;
