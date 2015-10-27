import 'mad/form/choice_element';
import 'mad/view/form/element/textbox';

/**
 * @parent Mad.form_api
 * @inherits mad.Component
 *
 * The Autocomplete Form Element
 * @todo TBD
 */
var Autocomplete = mad.form.Autocomplete = mad.form.Textbox.extend('mad.form.Autocomplete', /* @static */ {

    defaults: {
        label: 'Autocomplete Form Element Controller',
        tag: 'input'
    }

}, /** @prototype */ {

    afterStart: function() {
        // Add an list component
        // This list will allow the autocomplete component to display the available choices
        var listOpts = {
            itemClass: mad.Model,
            cssClasses: ['autocomplete-content'],
            templateUri: 'mad/view/template/component/tree.ejs',
            state: 'hidden',
            // The map to use to make jstree working with our category model
            map: new mad.Map({
                id: 'id',
                label: 'label',
                model: 'model'
            })
        };
        this.options.list = mad.helper.Component.create(this.element, 'after', mad.component.Tree, listOpts);
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
    '{list.element} item_selected': function(el, ev, data) {
        // update the value of the autocomplete field with the selected value
        this.setValue(data.label);
        // hide the autocomplete list
        this.options.list.setState('hidden');
        // Trigger the event on the main component.
        this.element.trigger('item_selected', [data, ev]);
    }

});

export default Autocomplete;
