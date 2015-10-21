import 'mad/view/component/tree';

/**
 * @inherits mad.view.component.Tree
 *
 * Our representation of the drop down menu
 *
 * @constructor
 * Instanciate a new Drop Down Menu view
 * @return {mad.view.component.tree.Jstree}
 */
var DropdownMenu = mad.view.component.DropdownMenu = mad.view.component.Tree.extend('mad.view.component.DropdownMenu', /* @static */ {}, /** @prototype */ {

    // Constructor like
    init: function (controller, options) {
        this._super(controller, options);
    },

    /**
     * Open an item
     * @param {mad.model.Model} item The target item to open
     * @return {void}
     */
    open: function (item) {
        var li = $('#' + item.id, this.element);
        li.removeClass('closed')
            .addClass('opened');
        var control = $('.control:first', li);
        control.removeClass('open')
            .addClass('close');
    },

    /**
     * Close an item
     * @param {mad.model.Model} item The target item to close
     * @return {void}
     */
    close: function (item) {
        var li = $('#' + item.id, this.element);
        li.removeClass('opened')
            .addClass('closed');
        var control = $('.control:first', li);
        control.removeClass('close')
            .addClass('open');
    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * Uncollapse an item
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    'li mouseover': function (el, ev) {
        ev.stopPropagation();
        ev.preventDefault();

        var data = null;
        if (this.getController().getItemClass()) {
            data = el.data(this.getController().getItemClass().fullName);
        } else {
            data = el[0].id;
        }

        this.element.trigger('item_opened', data);
    },

    /**
     * Uncollapse an item
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    'li mouseleave': function (el, ev) {
        ev.stopPropagation();
        ev.preventDefault();
        var data = null;

        if (this.getController().getItemClass()) {
            data = el.data(this.getController().getItemClass().fullName);
        } else {
            data = el[0].id;
        }
        this.element.trigger('item_closed', data);
    }

});