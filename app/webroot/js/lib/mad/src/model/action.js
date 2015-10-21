import 'mad/model/model';
import 'mad/model/state';

/**
 * @inherits mad.model.Model
 * @parent mad.component
 *
 * The Action model will carry actions used by other component
 *
 * @constructor
 * Creates a new Action
 * @param {array} options
 * @return {mad.model.Action}
 */
var Action = mad.model.Action = mad.Model.extend('mad.model.Action', /** @static */{

    /**
     * Define attributes of the model
     * @type {Object}
     */
    attributes: {
        'id': 'string',
        'label': 'string',
        'name': 'string',
        'icon': 'string',
        'action': 'function',
        'cssClasses': 'array',
        'initial_state': 'string',
        'state': mad.model.State.model,
        'active': 'boolean'
    }

}, /** @prototype */ {

    // Constructor like.
    init: function() {
        // Initialize the associated state instance. Byt default use the stateName defined in
        // the options.state
        if (typeof this.initial_state == 'undefined') {
            this.initial_state = 'ready';
        }
        this.state = new mad.model.State();
        this.state.setState(this.initial_state);

        // Instantiate the css classes variables if it has not been defined.
        // As the action is massively used by the
        if (typeof this.cssClasses == 'undefined' || this.cssClasses == null) {
            this.cssClasses = [];
        }
    },

    /**
     * Get the associated action
     * @return {function}
     */
    getAction: function () {
        return (typeof this.action != 'undefined') ? this.action : null;
    }
});

export default Action;