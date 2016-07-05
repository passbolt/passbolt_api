import 'mad/view/view';
import 'mad/helper/html';

/**
 * @inherits mad.View
 */
var Grid = mad.view.component.Grid = mad.View.extend('mad.view.component.Grid', /* @static */ {}, /** @prototype */ {

    /**
     * Flush the grid
     */
    reset: function () {
        $('tbody tr', this.element).remove();
    },

    /**
     * Hide a column
     * @param {string} columnName The column name to hide
     */
    hideColumn: function (columnName) {
        $('.js_grid_column_' + columnName, this.element).hide();
    },

    /**
     * Show a column
     * @param {string} columnName The column name to show
     */
    showColumn: function (columnName) {
        $('.js_grid_column_' + columnName, this.element).show();
    },

    /**
     * Select an item
     * @param {mixed} item The selected item instance or its id
     */
    selectItem: function (item) {
        var $item = this.getItemElement(item);
        $item.addClass('selected');
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
        $('tr.selected', this.element).removeClass('selected');
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

    /**
     * Remove an item from the grid
     * @param {mad.model.Model} item The item to remove
     */
    removeItem: function (item) {
        var $item = this.getItemElement(item);
        $item.remove();
    },

    /**
     * Hide an item.
     *
     * @param {mad.Model} item The item to hide.
     */
    hideItem: function (item) {
        var $item = this.getItemElement(item);
        $item.hide();
    },

    /**
     * Show an item.
     *
     * @param {mad.Model} item The item to show.
     */
    showItem: function (item) {
        var $item = this.getItemElement(item);
        $item.show();
    },

    /*
     * Render a row for a given item
     * @param {mad.model.Model} item
     */
    _renderRow: function (item) {
        var control = this.getController(),
            columnModels = control.getColumnModel(),
            mappedItem = control.getMap().mapObject(item),
        // The cells data (columnName -> value)
            values = [],
        // The cells titles data (columnName -> value)
            titles = [];

        // Build the row data.
        for (var i in columnModels) {
            var columnModel = columnModels[i],
                cellValue = null,
                titleValue = null;

            // Build the cell value.
            // A value adapter is provided for this cell.
            if (columnModel.valueAdapter) {
                cellValue = columnModel.valueAdapter(mappedItem[columnModel.name], mappedItem, item, columnModel);
            }
            // A widget adapter is provided for this cell, do nothing.
            // The widget will be applied on the cell once the row will be inserted in the DOM.
            else if (columnModel.widget || columnModel.cellAdapter) {
                cellValue = '';
            }
            // No transformer provided, use the mapped item data.
            else {
                cellValue = mappedItem[columnModel.name];
            }
            values[columnModel.name] = cellValue;

            // Build the title value.
            // A value adapter is provided for this title cell.
            if (columnModel.titleAdapter) {
                titleValue = columnModel.titleAdapter(mappedItem[columnModel.name], mappedItem, item, columnModel);
            }
            // No transformer provided, use the cell value as title value.
            else {
                titleValue = cellValue;
            }
            titles[columnModel.name] = titleValue;
        }

        // Render the row with the row data.
        return mad.View.render(control.options.itemTemplateUri, {
            item: item,
            id: control.options.prefixItemId + mappedItem.id,
            columnModels: columnModels,
            values: values,
            titles: titles
        });
    },

    /**
     * Get the HtmlElement that has been drawn for an item.
     *
     * @param item The item to get the element for.
     * @return {jQuery}
     */
    getItemElement: function (item) {
        return $('#' + this.getController().options.prefixItemId + item.id, this.element);
    },

    /**
     * Insert an item into the grid
     *
     * @param {mad.model.Model} item The item to insert in the grid
     * @param {mad.Model} refItem (optional) The reference item to use to position the new item.
     * By default the item will be inserted as last element of the grid.
     * @param {string} position (optional) If the reference item has been defined. The position
     * of the item to insert, regarding the reference item.
     */
    insertItem: function (item, refItem, position) {
        // By default position the new element inside as final element
        position = position || 'last';
        var $item = null,
            $refElement = null,
            itemRender = '',
            row = '',
            control = this.getController();

        // Adapt the insertion strategy regarding the reference element and the desired position.
        // By default the new item will be inserted as last elemement of the grid.
        switch (position) {
            case 'before':
            case 'after':
                // Get the HTML Element which represents the reference element.
                $refElement = this.getItemElement(refItem);
                if (!$refElement.length) {
                    throw new mad.Exception('No HTMLElement found for the given item.');
                }
                break;

            default:
            case 'first':
            case 'last':
                // By default retrieve the content HTML element of the grid as reference element.
                $refElement = $('tbody', this.element);
                break;
        }

        // Render the row.
        row = this._renderRow(item);

        // Insert the row html fragment in the grid.
        $item = mad.helper.Html.create($refElement, position, row);
        // Associate to the item to the just created node
        can.data($item, control.getItemClass().fullName, item);

        return $item;
    },

    /**
     * Refresh an item.
     * @param {mad.model.Model} item The item to refresh
     */
    refreshItem: function (item) {
        // Get the HTML Element which represents the item.
        var $current = this.getItemElement(item);

        // Render the row with the new item values.
        var row = this._renderRow(item);

        // Replace the previous row with the new one.
        var $item = mad.helper.Html.create($current, 'replace_with', row);
        // Associate to the item to the just created node
        can.data($item, this.getController().getItemClass().fullName, item);
    },

    /**
     * Move an item to another position in the grid.
     * @param item The item to move
     * @param position The position to move the item to
     */
    moveItem: function (item, position) {
        var $el = this.getItemElement(item),
            $detachedEl = $el.detach(),
            $refEl = $('tbody tr', this.element).eq(position);

        if ($refEl.length) {
            $refEl.before($detachedEl);
        } else {
            $('tbody', this.element).append($detachedEl);
        }
    },

    /**
     * Mark the column as sorted
     * @param columnModel
     * @param sortAsc
     */
    markColumnAsSorted: function (columnModel, sortAsc) {
        var cssClasses = 'sorted ';
        cssClasses += sortAsc ? 'sort-asc' : 'sort-desc';
        $('.sortable.sorted').removeClass('sorted sort-asc sort-desc');
        $('.js_grid_column_' + columnModel.name, this.element).addClass(cssClasses);
    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * A sort is requested on a column
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     */
    'thead th.sortable click': function (el, ev) {
        ev.preventDefault();

        var columnModel = null,
            control = this.getController(),
            sortAsc = true;

        // If the column is already sorted.
        if ($(el).hasClass('sorted')) {
            if ($(el).hasClass('sort-desc')) {
                sortAsc = true;
            } else {
                sortAsc = false;
            }
        }

        columnModel = el.data(control.getColumnModelClass().fullName);
        this.element.trigger('column_sort', [columnModel, sortAsc, ev]);
    },

    /**
     * An item has been selected
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     */
    'tbody tr click': function (el, ev) {
        var data = null,
            control = this.getController(),
            itemClass = control.getItemClass();

        if (itemClass) {
            data = el.data(itemClass.fullName);
        } else {
            data = el[0].id.replace(control.options.prefixItemId, '');
        }

        this.element.trigger('item_selected', [data, ev]);
    },

    /**
     * An item has been hovered
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     */
    'tbody tr hover': function (el, ev) {
        var data = null,
            control = this.getController(),
            itemClass = control.getItemClass();

        if (itemClass) {
            data = el.data(itemClass.fullName);
        } else {
            data = el[0].id.replace(control.options.prefixItemId, '');
        }

        this.element.trigger('item_hovered', [data, ev]);
    }

});

export default Grid;
