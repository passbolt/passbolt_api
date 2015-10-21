import 'mad/component/component';
import 'mad/view/component/grid';
import 'mad/view/template/component/grid/grid.ejs!';
import 'mad/view/template/component/grid/gridItem.ejs!';

/**
 * @parent Mad.components_api
 * @inherits mad.Component
 * @group mad.component.Grid.view_events 0 View Events
 *
 * The Grid Component as for aim to display a data grid.
 * @todo TBD
 */
var Grid = mad.component.Grid = mad.Component.extend('mad.component.Grid', {

    defaults: {
        // Override the label option.
        label: 'Grid Component',
        // Override the cssClasses option.
        cssClasses: ['tableview'],
        // Override the tag option.
        tag: 'div',
        // Override the templateUri option.
        templateUri: 'mad/view/template/component/grid/grid.ejs',
        // The component deals with sub template to render the grid row.
        itemTemplateUri: 'mad/view/template/component/grid/gridItem.ejs',
        // Override the viewClass option.
        viewClass: mad.view.component.Grid,
        // Prefix the id of each row.
        prefixItemId: '',
        // The Model Class that defines the items displayed by the tree.
        itemClass: null,
        // the grid column model
        columnModel: [],
        // The map used to transform the raw data into expected view format.
        map: null,
        // The callbacks the component offers to the dev to bind their code.
        callbacks: {
            // An item is left click selected.
            item_selected: null,
            // An item is hovered.
            item_hovered: null
        }
    }

}, /** @prototype */ {

    /**
     * Flush the grid
     */
    reset: function () {
        this.view.reset();
    },

    /**
     * Before render.
     */
    beforeRender: function () {
        this._super();
        this.setViewData('columnModel', this.options.columnModel);
        this.setViewData('items', []);
    },

    /**
     * Get the column model of the grid.
     *
     * @return {mad.model.Model}
     */
    getColumnModel: function () {
        return this.options.columnModel;
    },

    /**
     * Get the itemClass which represents the items managed by the component.
     *
     * @return {mad.model.Model}
     */
    getItemClass: function () {
        return this.options.itemClass;
    },

    /**
     * Set the itemClass which represents the items managed by the component.
     *
     * @param {mad.model.Model} itemClass The item class
     */
    setItemClass: function (itemClass) {
        this.options.itemClass = itemClass;
    },

    /**
     * Get the associated map, which will be used to map the model data to the
     * expected view format.
     *
     * @return {mad.object.Map}
     */
    getMap: function (map) {
        return this.options.map;
    },

    /**
     * Set the associated map, which will be used to map the model data to the
     * expected view format.
     *
     * @param {mad.object.Map} map The map
     */
    setMap: function (map) {
        this.options.map = map;
    },

    /**
     * Select an item.
     *
     * @param {mad.Model}
     */
    selectItem: function (item) {
        this.view.selectItem(item);
    },

    /**
     * Right select an item.
     *
     * @param {mad.Model}
     */
    rightSelectItem: function (item) {
        this.view.rightSelectItem(item);
    },

    /**
     * Unselect an item.
     * @param {mad.Model}
     * @todo unselectItem calls the unselectAll view function, check where this function is used and correct this logic problem.
     */
    unselectItem: function (item) {
        this.view.unselectAll();
    },

    /**
     * Hover an item.
     *
     * @param {mad.Model}
     */
    hoverItem: function (item) {
        this.view.hoverItem(item);
    },

    /**
     * Unselect all the previously selected items.
     */
    unselectAll: function () {
        this.view.unselectAll();
    },

    /**
     * Remove an item from the grid.
     *
     * @param {mad.model.Model} item The item to remove
     */
    removeItem: function (item) {
        // Remove the item to the view
        this.view.removeItem(item);
    },

    /**
     * Insert an item into the grid.
     *
     * @param {mad.model.Model} item The item to insert
     * @param {mad.Model} refItem (optional) The reference item to use to position the new item.
     * By default the item will be inserted as last element of the grid.
     * @param {string} position (optional) If the reference item has been defined. The position
     * of the item to insert, regarding the reference item.
     *
     * Available values : before, after, first, last.
     *
     * By default last.
     */
    insertItem: function (item, refItem, position) {
        var self = this,
            map = this.getMap(),
            mappedItem = null,
            columnModels = this.getColumnModel();

        // An item should be given as parameter and valid.
        if (this.getItemClass() != null && !(item instanceof this.getItemClass())) {
            throw mad.Exception.get(mad.error.WRONG_PARAMETER, 'item');
        }

        // A map should be defined and valid.
        if (map == null) {
            throw mad.Exception.get(mad.error.MISSING_OPTION, 'map');
        }

        // Map the item.
        mappedItem = this.getMap().mapObject(item);

        // insert the item in the view
        this.view.insertItem(item, refItem, position);

        // Apply widgets to the cells following the definition in the columns model.
        for (var j in columnModels) {
            var columnModel = columnModels[j];

            if (columnModel.cellAdapter) {
                var itemId = self.options.prefixItemId + mappedItem.id;
                var $cell = $('#' + itemId + ' .js_grid_column_' + columnModel.name + ' div');
                var cellValue = mappedItem[columnModel.name];
                columnModel.cellAdapter($cell, cellValue, mappedItem, item, columnModel);
            }
            // @todo Cell adapter replace widget, remove this part if not usefull
            if (columnModel.widget) {
                var widgetClass = columnModel.widget.clazz,
                    widgetJQueryPlugin = widgetClass._fullName,
                    widgetOptions = columnModel.widget.options;

                // Ok it is costing : + z*n (z #columWidget; n #items) with this
                // part to insert the items and render widget if there is
                var itemId = self.options.prefixItemId + mappedItem[i].id;
                var $cell = $('#' + itemId + ' .js_grid_column_' + columnModel.name + ' div');
                widgetOptions.value = mappedItem[i][columnModel.name];
                $cell[widgetJQueryPlugin](widgetOptions);
                $cell[widgetJQueryPlugin]('render');
            }
        }
    },

    /**
     * Refresh an item.
     *
     * @param {mad.model.Model} item The item to refresh
     */
    refreshItem: function (item) {
        this.view.refreshItem(item);

        var self = this,
            mappedItem = this.getMap().mapObject(item),
            columnModels = this.getColumnModel();

        // apply a widget to cells following the columns model
        for (var j in columnModels) {
            var columnModel = columnModels[j];

            if (columnModel.cellAdapter) {
                var itemId = self.options.prefixItemId + mappedItem.id;
                var $cell = $('#' + itemId + ' .js_grid_column_' + columnModel.name + ' div');
                var cellValue = mappedItem[columnModel.name];
                columnModel.cellAdapter($cell, cellValue, mappedItem, item, columnModel);
            }
            // @todo Cell adapter replace widget, remove this part if not usefull
            if (columnModel.widget) {
                var widgetClass = columnModel.widget.clazz,
                    widgetJQueryPlugin = widgetClass._fullName,
                    widgetOptions = columnModel.widget.options;

                // Ok it's costly : + z*n (z #columWidget; n #items) with this
                // part to insert the items and render widget if there is
                var itemId = self.options.prefixItemId + mappedItem[i].id;
                var $cell = $('#' + itemId + ' .js_grid_column_' + columnModel.name + ' div');
                widgetOptions.value = mappedItem[i][columnModel.name];
                $cell[widgetJQueryPlugin](widgetOptions);
                $cell[widgetJQueryPlugin]('render');
            }
        }
    },

    /**
     * Load items in the grid. If the grid contain items, reset it.
     *
     * @param {mad.model.Model[]} items The array or list of items to insert in the grid
     */
    load: function (items) {
        var self = this;

        this.reset();
        can.each(items, function (item, i) {
            self.insertItem(item);
        });

        return this;
    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * @function mad.component.Grid.tbody__mouseleave
     * @parent mad.component.Grid.view_events
     *
     * Observe when the mouse leave the main area of component. It does not include
     * table header.
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     */
    'tbody mouseleave': function (element, evt) {
        //
    },

    /**
     * @function mad.component.Grid.__item_selected
     * @parent mad.component.Grid.view_events
     *
     * Observe when an item is selected.
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     * @param {mixed} item The selected item instance or its id
     * @param {HTMLEvent} srcEvent The source event which occurred
     */
    ' item_selected': function (el, ev, item, srcEvent) {
        // override this function, call _super if you want the default behavior processed
        if (this.options.callbacks.itemSelected) {
            this.options.callbacks.itemSelected(el, ev, item, srcEvent);
        }
    },

    /**
     * @function mad.component.Grid.__item_hovered
     * @parent mad.component.Grid.view_events
     *
     * Observe when an item has been hovered.
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     * @param {mixed} item The hovered item instance or its id
     * @param {HTMLEvent} srcEvent The source event which occurred
     */
    ' item_hovered': function (el, ev, item, srcEvent) {
        // override this function, call _super if you want the default behavior processed
        if (this.options.callbacks.itemHovered) {
            this.options.callbacks.itemHovered(el, ev, item, srcEvent);
        }
    }
});

export default Grid;
