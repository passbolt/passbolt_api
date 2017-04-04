import 'mad/view/view';
import 'app/view/template/component/group_edit.ejs!';


/**
 * @inherits mad.view.View
 */
var GroupEdit = passbolt.view.component.GroupEdit = mad.View.extend('passbolt.view.component.GroupEdit', /** @static */ { }, /** @prototype */ {

    /* ************************************************************** */
    /* LISTEN TO VIEW EVENTS */
    /* ************************************************************** */

    /**
     * Observe when the user want to delete a groupUser.
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    ' .js_group_user_delete click': function(el, ev) {
        ev.stopPropagation();
        ev.preventDefault();

        var li = el.parents('li');
        var groupUser = li.data('passbolt.model.GroupUser');
        this.element.trigger('request_group_user_delete', [groupUser]);
    },

    /**
     * Observe when the user want to edit a permission type.
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    ' .js_group_user_is_admin changed': function(el, ev, data) {
        ev.stopPropagation();
        ev.preventDefault();

        var li = el.parents('li'),
            groupUser = li.data('passbolt.model.GroupUser');

        this.element.trigger('request_group_user_edit', [groupUser, data.value]);
    },

    // /**
    //  * Observe when the user want to reset the filter
    //  * @param {HTMLElement} el The element the event occurred on
    //  * @param {HTMLEvent} ev The event which occurred
    //  */
    // ' #js_perm_create_form_add_btn click': function(el, ev) {
    //     ev.stopPropagation();
    //     ev.preventDefault();
    //
    //     el.trigger('submit');
    // }

});
