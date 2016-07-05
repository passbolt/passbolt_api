import 'mad/model/model';

/**
 * @inherits mad.model.Model
 * @parent mad.model
 *
 * The GridColumn model will carry grid column settings
 *
 * @constructor
 * Creates a new GridColumn
 * @param {array} options
 * @return {mad.model.GridColumn}
 */
var GridColumn = mad.model.GridColumn = can.Map.extend('mad.model.GridColumn', /** @prototype */ {

    define: {
        // Name of the column
        // @todo Check if we can drop the name or the index attribute.
        name: {
            type: 'string'
        },
        // Index of the column.
        index: {
            type: 'string'
        },
        // Is the column sortable.
        sortable: {
            type: 'boolean',
            value: false
        },
        // Label of the column.
        label: {
            type: 'string'
        },
        // CSS classes to add to the column header element (th).
        css: {
            Type: Array,
            value: []
        },
        // The column cells will be formated based on an function adapter.
        cellAdapter: {
            type: '*',
            value: null
        },
        // The column cells values will be formated based on an function adapter.
        // @todo Is this function still used, the mapping of the data should do the job.
        valueAdapter: {
            type: '*',
            value: null
        }
    },

    // Constructor like
    init: function(options) {
        this._super(options);

        // If sortable
        if (this.sortable) {
            // Add the class sortable to CSS classes.
            this.css.push('sortable');
        }
    }

});

export default GridColumn;
