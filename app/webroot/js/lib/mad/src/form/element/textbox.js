import 'mad/form/element';
import 'mad/view/form/element/textbox';

/**
 * @parent Mad.form_api
 * @inherits mad.Component
 *
 * The Textbox Form Element
 * @todo TBD
 */
var Textbox = mad.form.Textbox = mad.form.Element.extend('mad.form.Textbox', /* @static */ {

    defaults: {
        // Override the label option.
        label: 'Textbox Form Element',
        // Override the tag option.
        tag: 'input',
        // Override the viewClass option.
        viewClass: mad.view.form.Textbox,

        // Add a period of time before firing the changed event.
        // By instance while typing into a textbox, you'd like to be notified that the value
        // of the textbox changed after the user inserted the while string, not after each character.
        onChangeTimeout: 0,

        // Add a length limit before firing the changed event.
        // By instance on an autocomplete textbox, you'd like to broadcast the changed event only
        // when the value length is greater than 2.
        onChangeAfterLength: 0
    }

}, /** @prototype */ {});

export default Textbox;
