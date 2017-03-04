import 'mad/component/tree';
import 'app/model/group';
import 'app/view/template/component/group_item.ejs!';

/*
 * @class passbolt.component.GroupsList
 * @inherits mad.component.Tree
 * @parent index
 *
 * UserGroupsList component.
 * It contains a groups list.
 *
 * @constructor
 * Creates a new Groups List Controller.
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.controller.UserGroupsList}
 */
var GroupsList = passbolt.component.GroupsList = mad.component.Tree.extend('passbolt.component.GroupsList', /** @static */ {

    defaults: {
        selfLoad:false,
        itemClass: passbolt.model.Group,
        templateUri: 'mad/view/template/component/tree.ejs',
        itemTemplateUri: 'js/app/view/template/component/group_item.ejs'
    }

}, /** @prototype */ {

    /**
     * Init callback.
     * @param el
     * @param opts
     */
    init: function (el, opts) {
        this._super(el, opts);
        var self = this;
        // Load the groups.
        passbolt.model.Group.findAll({}, function (groups, response, request) {
            // Load the tree component with the groups.
            self.load(groups);
        });
    }
});

export default GroupsList;