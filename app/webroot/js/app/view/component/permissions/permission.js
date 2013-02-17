steal(
  'mad/view'
).then(function () {

  /*
   * @class passbolt.view.component.Permissions
   * @inherits mad.view.View
   */
  mad.view.View.extend('passbolt.view.component.permissions.Permission', /** @static */ {

  }, /** @prototype */ {

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * Observe when the user clicks on the h2 event, rolldown the following p tag
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    '#js_field_aco click': function (el, ev) {
      console.log("clicked");
    }
  });
});