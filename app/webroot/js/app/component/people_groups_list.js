import 'app/component/groups_list';
import 'app/model/group';
import 'app/view/component/groups_list';
import 'app/view/template/component/group_item.ejs!';

/**
 * @class passbolt.component.PeopleGroupsList
 * @inherits passbolt.component.groups_list
 * @parent index
 *
 * PeopleGroupsList component.
 * It contains a groups list designed for the user workspace.
 *
 * @constructor
 * Creates a new Password Categories Groups List Component.
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.controller.PasswordCategoriesGroupsList}
 */
var PeopleGroupsList = passbolt.component.PeopleGroupsList = passbolt.component.GroupsList.extend('passbolt.component.PeopleGroupsList', /** @static */ {

  defaults: {
    withMenu: true
  }

}, /** @prototype */ {

  /**
   * Show the contextual menu
   * @param {passbolt.model.Resource} item The item to show the contextual menu for
   * @param {string} x The x position where the menu will be rendered
   * @param {string} y The y position where the menu will be rendered
   * @param {HTMLElement} eventTarget The element the event occurred on
   */
  showContextualMenu: function (item, x, y, eventTarget) {

    var currentUser = passbolt.model.User.getCurrent(),
      isAdmin = (currentUser.Role.name == 'admin');

    // Get the offset position of the clicked item.
    var $item = $('#' + this.options.prefixItemId + item.id);
    var item_offset = $('.more-ctrl a', $item).offset();

    // Instantiate the contextual menu menu.
    var contextualMenu = new mad.component.ContextualMenu(null, {
      state: 'hidden',
      source: eventTarget,
      coordinates: {
        x: x,
        y: item_offset.top
      }
    });
    contextualMenu.start();

    // Add Edit group action.
    var action = new mad.model.Action({
      id: 'js_group_browser_menu_edit',
      label: 'Edit group',
      initial_state: 'ready',
      action: function (menu) {
        mad.bus.trigger('request_group_edition', item);
        menu.remove();
      }
    });
    contextualMenu.insertItem(action);

    // Add Delete group action if the user is an admin.
    if (isAdmin) {
      var action = new mad.model.Action({
        id: 'js_group_browser_menu_remove',
        label: 'Delete group',
        initial_state: 'ready',
        action: function (menu) {
          // var secret = item.Secret[0].data;
          mad.bus.trigger('request_group_deletion', item);
          menu.remove();
        }
      });
      contextualMenu.insertItem(action);
    }

    // Display the menu.
    contextualMenu.setState('ready');
  },


  /* ************************************************************** */
  /* LISTEN TO THE VIEW EVENTS */
  /* ************************************************************** */

  /**
   * An item has been clicked on the menu icon
   * @param {HTMLElement} el The element the event occurred on
   * @param {HTMLEvent} ev The event which occurred
   * @param {passbolt.model.Group} item The right selected item instance or its id
   * @param {HTMLEvent} srcEvent The source event which occurred
   */
  ' item_menu_clicked': function (el, ev, item, srcEvent) {
    // Show contextual menu.
    this.showContextualMenu(item, srcEvent.pageX, srcEvent.pageY, srcEvent.target);
  }

});

export default PeopleGroupsList;