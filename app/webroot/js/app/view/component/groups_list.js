import 'mad/view/component/grid';
import 'app/view/template/component/group_item.ejs!';

/*
 * @inherits mad.view.Grid
 */
var GroupsList = passbolt.view.component.GroupsList = mad.view.component.Tree.extend('passbolt.view.component.GroupsList', /** @static */ {

}, /** @prototype */ {

    /**
     * Mousedown event.
     *
     * We use this event to display the contextual menu
     * @param el
     * @param ev
     * @returns {boolean}
     */
    '.more-ctrl a mousedown': function (el, ev) {
        ev.stopPropagation();
        ev.preventDefault();

        var data = null,
            li = el.closest('li');

        if (this.getController().getItemClass()) {
            data = li.data(this.getController().getItemClass().fullName);
        } else {
            data = li[0].id;
        }

        this.element.trigger('item_menu_clicked', [data, ev]);

        return false;
    }

});

export default GroupsList;