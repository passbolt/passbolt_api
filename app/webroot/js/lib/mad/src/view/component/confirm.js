
/**
 * @inherits mad.view.View
 */
var ConfirmView = mad.view.component.Confirm = mad.view.component.Dialog.extend('mad.view.component.Confirm', /* @static */ {}, /** @prototype */ {

    /**
     * Set the content
     * @param {string} title The new content
     */
    setContent: function (content) {
        $('.dialog-content .form-content', this.element).html(content);
    },

    /**
     * Listen to click on the confirm link
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    ' .js-dialog-confirm click': function (el, ev) {
        ev.preventDefault();
        this.element.trigger('confirm_clicked');
    }
});

export default ConfirmView;
