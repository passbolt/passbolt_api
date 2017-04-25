import 'mad/component/dialog';
import 'mad/view/component/confirm';
import 'mad/view/template/component/confirm/confirm.ejs!';

var Confirm = mad.component.Confirm = mad.component.Dialog.extend('mad.component.Confirm', /** @static */ {

    defaults: {
        label: 'Confirm component',
        viewClass: mad.view.component.Confirm,
        templateUri: 'mad/view/template/component/confirm/confirm.ejs',
        subtitle: null,
        submitButton: {
            label: __('OK'),
            cssClasses: [
                // Extra css classes.
                // Will be added to button, primary, js-dialog-confirm
            ]
        },
        content: '',
        closeAfterAction: true,
        action: null
    }


}, /** @prototype */ {

    /**
     * Init.
     * @param el
     * @param options
     */
    init: function(el, options) {
        this._super(el, options);
        this.setViewData('content', this.options.content);
        this.setViewData('subtitle', this.options.subtitle);
        this.setViewData('submitButton', this.options.submitButton);
    },

    /**
     * Set the content
     * @param {string} content the new Content
     */
    setContent: function(content) {
        this.view.setContent(content);
    },

    /**
     * confirm_clicked event
     * thrown when the user has clicked on confirm.
     */
    ' confirm_clicked': function() {
        if (typeof this.options.action !== 'undefined') {
            this.options.action();
        }
        if (this.options.closeAfterAction === true) {
            mad.component.Confirm.closeLatest();
        }
    }
});

export default Confirm;