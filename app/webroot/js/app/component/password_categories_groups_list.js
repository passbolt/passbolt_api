import 'app/component/groups_list';
import 'app/model/group';
import 'app/view/component/groups_list';
import 'app/view/template/component/group_item.ejs!';

/**
 * @class passbolt.component.PasswordCategoriesGroupsList
 * @inherits passbolt.component.groups_list
 * @parent index
 *
 * PasswordCategoriesGroupsList component.
 * It contains a groups list designed for passwords workspace.
 *
 * @constructor
 * Creates a new Password Categories Groups List Component.
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.controller.PasswordCategoriesGroupsList}
 */
var PasswordCategoriesGroupsList = passbolt.component.PasswordCategoriesGroupsList = passbolt.component.GroupsList.extend('passbolt.component.PasswordCategoriesGroupsList', /** @static */ {

  defaults: {
    itemClass: passbolt.model.Group,
    templateUri: 'mad/view/template/component/tree.ejs',
    itemTemplateUri: 'app/view/template/component/group_item.ejs',
    prefixItemId: 'group_',
    selectedGroups: can.Model.List(),
    selectedFilter: null,
    // the view class to use. Overriden so we can put our own logic.
    viewClass: passbolt.view.component.GroupsList
  }

}, /** @prototype */ {

  /* ************************************************************** */
  /* LISTEN TO THE APP EVENTS */
  /* ************************************************************** */
  /**
   * Select a group.
   * Overrides the parent select function which only filters users and not resources.
   * @param {passbolt.model.Group} group The group to filter the workspace with
   */
  select: function (group) {
    this.view.selectItem(group);

    // Reset the list of selected groups and add the new selected one.
    this.options.selectedGroups.splice(0, this.options.selectedGroups.length);
    this.options.selectedGroups.push(group);

    // Propagate the filter by group component.
    this.selectedFilter = new passbolt.model.Filter({
      id: 'workspace_filter_group_' + group.id,
      label: group.name + __(' (group)'),
      rules: {
        'is-shared-with-group': group.id
      }
    });
    mad.bus.trigger('filter_workspace', this.selectedFilter);
  }

});

export default PasswordCategoriesGroupsList;