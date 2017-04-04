import 'mad/view/view';
import 'mad/helper/html';

/**
 * @inherits mad.View
 */
var Tree = mad.view.component.Tree = mad.View.extend('mad.view.component.Tree', /** @static */ {}, /** @prototype */ {

    /**
     * Get the HtmlElement that has been drawn for an item.
     *
     * @param item The item to get the element for.
     * @return {jQuery}
     */
    getItemElement: function (item) {
        var control = this.getController();
        return $('#' + control.options.prefixItemId + item.id, this.element);
    },

    /**
     * Insert an item into the tree.
     *
     * @param {mad.Model} item The item to insert.
     * @param {mad.Model} refItem (optional) The reference item to use to position the new item.
     * By default the item will be inserted at the end of the tree.
     * @param {string} position (optional) If the reference item has been defined. The position
     * of the item to insert, regarding the reference item.
     *
     * Available values : before, after, inside, first, last.
     *
     * By default last.
     *
     * @return {jQuery} The inserted HtmlElement.
     */
    insertItem: function (item, refItem, position) {
        position = position || 'last';
        var self = this,
            $item = null,
            $refElement = null,
            itemRender = '',
            control = this.getController();

        // Map the given item, and set view's data.
        var mappedItem = control.getMap().mapObject(item);
        if (control.options.prefixItemId != '' && control.options.prefixItemId != undefined) {
            mappedItem.id = control.options.prefixItemId +  mappedItem.id;
        }
        control.setViewData('mappedItem', mappedItem);

        // Does the item has children ?
        var hasChildren = mappedItem.children && mappedItem.children.length ? true : false;
        control.setViewData('hasChildren', hasChildren);

        // Retrive custom item css classes.
        var cssClasses = [];
        if (typeof mappedItem['cssClasses'] != 'undefined') {
            cssClasses = cssClasses.concat(mappedItem['cssClasses']);
        }
        control.setViewData('cssClasses', cssClasses);

        // Find the HTML reference Element.
        if (refItem !== undefined && refItem !== null) {
            $refElement = this.getItemElement(refItem);
            if (!$refElement.length) {
                throw new mad.Exception.get('No HTMLElement found for the given item (%0).', [refItem.id]);
            }

            // Regarding the position and the reference element, define the reference list the item will be inserted in.
            switch (position) {
                case 'first':
                case 'last':
                case 'inside':
                    // Retrieve the first <ul> HTMLElement in the given reference element.
                    var $refList = $refElement.find('ul:first');
                    // The reference element doesn't have any <ul> HTMLElement. Create it.
                    // It happened when the reference element was not a parent before that very moment ;) Good luck.
                    if (!$refList.length) {
                        $refElement = $('<ul></ul>').appendTo($refElement);
                    } else {
                        $refElement = $refList;
                    }
                    break;

                case 'before':
                case 'after':
                    // The HTML reference element is the one find based on the item given in parameter.
                    $refElement = $refElement;
                    break;
            }
        }
        // If no reference item given, the item will be inserted in the root <ul>.
        // The tree component is always associated to an <ul> HTMLElement. This is the root <ul>.
        else {
            $refElement = this.element;
        }

        // Render the item.
        itemRender = mad.View.render(control.options.itemTemplateUri, control.getViewData());
        // Insert it in the DOM and position it.
        $item = mad.helper.Html.create($refElement, position, itemRender);
        // Associate to the item to the just created node
        can.data($item, control.getItemClass().fullName, item);

        return $item;
    },

    /**
     * Remove an item from the tree
     *
     * @param {mad.Model} item The target item to remove
     */
    removeItem: function (item) {
        this.getItemElement(item).remove();
    },

    /**
     * Refresh an item in the tree
     *
     * @param {mad.Model} item The item to refresh
     */
    refreshItem: function (item) {
        var self = this,
            $item = this.getItemElement(item),
            control = this.getController();

        // map the given data to the desired format
        var mappedItem = control.getMap().mapObject(item);;
        control.setViewData('mappedItem', mappedItem);
        var hasChildren = mappedItem.children && mappedItem.children.length ? true : false;
        control.setViewData('hasChildren', hasChildren);

        // Retrieve custom item css classes.
        var cssClasses = [];
        if (typeof mappedItem['cssClasses'] != 'undefined') {
            cssClasses = cssClasses.concat(mappedItem['cssClasses']);
        }
        control.setViewData('cssClasses', cssClasses);

        // Render the item.
        var itemRender = mad.View.render(control.options.itemTemplateUri, control.getViewData());
        // Replace the item row with its updated version.
        $item.replaceWith(itemRender);
        $item = this.getItemElement(item);
        // Associate to the item to the just created node.
        can.data($item, control.getItemClass().fullName, item);

        if (hasChildren) {
            can.each(item.children, function (item, i) {
                self.insertItem(item, mappedItem.id, 'last');
            });
        }

        return $item;
    },

    /**
     * Reset the view by removing all the items
     */
    reset: function () {
        $('li', this.element).remove();
    },

    /**
     * An item has been selected
     *
     * @param {mad.Model} item The selected item instance or its id
     */
    selectItem: function (item) {
        this.unselectAll();
        var $item = this.getItemElement(item);
        $('.row:first', $item).addClass('selected');
    },

    /**
     * Unselect an item
     * @param {mixed} item The unselected item instance or its id
     */
    unselectItem: function (item) {
        var $item = this.getItemElement(item);
        $item.removeClass('selected');
    },

    /**
     * Unselect all.
     */
    unselectAll: function () {
        $('.row.selected', this.element).removeClass('selected');
    },

    /**
     * An item has been right selected
     *
     * @param {mad.Model} item The selected item instance or its id
     */
    rightSelectItem: function (item) {
    },

    /**
     * An item has been hovered
     *
     * @param {mad.Model} item The selected item instance or its id
     */
    hoverItem: function (item, element, srcEvent) {
    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * An item has been selected
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     */
    'li .main-cell a click': function (el, ev) {
        ev.stopPropagation();
        ev.preventDefault();

        var data = null,
            li = el.parents('li:first'),
            itemClass = this.getController().getItemClass();

        if (itemClass) {
            data = li.data(itemClass.fullName);
        } else {
            data = li[0].id;
        }

        this.element.trigger('item_selected', [data, ev]);
        return false;
    },

    /**
     * An item has been right selected
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     */
    'li a contextmenu': function (el, ev) {
        ev.stopPropagation();
        ev.preventDefault();

        if (ev.which == 3) {
            var data = null,
                li = el.parents('li:first'),
                itemClass = this.getController().getItemClass();

            if (itemClass) {
                data = li.data(itemClass.fullName);
            } else {
                data = li[0].id;
            }

            this.element.trigger('item_right_selected', [data, ev]);
        }

        return false;
    },

    /**
     * An item has been hovered
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     */
    'li a hover': function (el, ev) {
        ev.stopPropagation();
        ev.preventDefault();

        var data = null,
            li = el.parents('li:first'),
            itemClass = this.getController().getItemClass();

        if (itemClass) {
            data = li.data(itemClass.fullName);
        } else {
            data = li[0].id;
        }

        this.element.trigger('item_hovered', [data, ev]);
        return false;
    }

});

export default Tree;
