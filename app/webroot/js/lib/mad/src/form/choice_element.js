import 'mad/form/element';
import 'mad/view/form/element';

/**
 * @parent Mad.form_api
 * @inherits mad.Component
 *
 * The Choice Form Element.
 * This form element shouldn't be instantiated.
 * @todo TBD
 */
var ChoiceElement = mad.form.ChoiceElement = mad.form.Element.extend('mad.form.ChoiceElement', /* @static */ {

    defaults: {
        // The form choice element available values.
        availableValues: {}
    }

}, /** @prototype */ {

    /**
     * Implements beforeRender hook().
     */
    'beforeRender': function() {
        this._super();
        this.setViewData('availableValues', this.options.availableValues);
    }

});

export default ChoiceElement;
