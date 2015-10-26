import 'mad/view/form/element';

/**
 * @inherits mad.view.form.Element
 */
var Checkbox = mad.view.form.Checkbox = mad.view.form.Element.extend('mad.view.form.Checkbox', /* @static */ {

}, /** @prototype */ {

    /**
     * Get the value of the checkbox form element
     *
     * @return {array}
     */
    getValue: function () {
        var returnValue = [];
        this.element.find('input:checked').each(function () {
            returnValue.push($(this).val());
        });
        return returnValue;
    },

    /**
     * Set the value of the checkbox form element
     *
     * @param {array} value An array containing the value to check
     */
    setValue: function (value) {
        value = typeof value != 'undefined' && value != null ? value : [];
        this.element.find('input').each(function () {
            // if the value of the input is found in the array of value given, check the box
            if (value.indexOf($(this).val()) != -1) {
                $(this).attr('checked', true);
                $(this)[0].checked = true;
            } else {
                $(this)[0].checked = false;
                $(this).removeAttr('checked');
            }
        });
    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * Listen to the view event click
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     */
    'input click': function (el, ev) {
        ev.stopPropagation();

        if (el.is(':checked')) {
            this.element.trigger('checked', el.val());
        }
        else {
            this.element.trigger('unchecked', el.val());
        }
    },

    /**
     * Listen to the view event change
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     */
    'input change': function (el, ev) {
        ev.stopPropagation();

        this.element.trigger('changed', {value: this.getValue()});
    }
});

export default Checkbox;
