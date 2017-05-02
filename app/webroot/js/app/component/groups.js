import 'mad/component/component';
import 'app/component/groups_list';
import 'app/view/template/component/groups.ejs!';

/*
 * @class passbolt.component.Groups
 * @inherits mad.Component
 * @parent index
 *
 * Groups component.
 * It contains a groups list, with a filtering menu.
 *
 * @constructor
 * Creates a new User Groups Controller.
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.controller.Groups}
 */
var Groups = passbolt.component.Groups = mad.Component.extend('passbolt.component.Groups', /** @static */ {

    defaults: {
        templateUri: 'app/view/template/component/groups.ejs',
        selectedGroups: new can.Model.List()
    }

}, /** @prototype */ {

    /**
     * After start hook.
     * @see {mad.Component}
     */
    afterStart: function() {
        var groupsList = new passbolt.component.GroupsList($('#js_wsp_users_groups_list', this.element), {
            selectedGroups: this.options.selectedGroups
        });
        groupsList.start();
    }

});

export default Groups;