import 'mad/component/component';
import 'app/component/password_categories_groups_list';
import 'app/view/template/component/password_categories.ejs!';

/*
 * @class passbolt.component.PasswordCategories
 * @inherits mad.Component
 * @parent index
 *
 * PasswordCategories component.
 * It contains a list of groups and categories that can be displayed alternatively by the user.
 * The first version only contains a list of groups.
 *
 * @constructor
 * Creates a new Password Categories component.
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.controller.PasswordCategories}
 */
var PasswordCategories = passbolt.component.PasswordCategories = mad.Component.extend('passbolt.component.PasswordCategories', /** @static */ {

  defaults: {
    templateUri: 'app/view/template/component/password_categories.ejs',
    selectedGroups: new can.Model.List(),
    state: 'hidden' // Is hidden by default, and will be displayed only when there are groups to show.
  }

}, /** @prototype */ {

  /**
   * After start hook.
   * @see {mad.Component}
   */
  afterStart: function() {
    var self = this;
    var passwordCategoriesGroupsList = new passbolt.component.PasswordCategoriesGroupsList($('#js_wsp_password_categories_groups_list', this.element), {
      selectedGroups: this.options.selectedGroups,
      defaultGroupFilter: {
        "has-users" : passbolt.model.User.getCurrent().id
      },
      afterLoad: function(groups) {
        if (groups.length > 0) {
          self.setState('ready');
        }
      }
    });
    passwordCategoriesGroupsList.start();
  }
});

export default PasswordCategories;