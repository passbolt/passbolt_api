//import 'jquery/event/drag';
import 'mad/view/component/grid';
import 'app/view/template/component/user/dragged_user.ejs!';

/*
 * @class passbolt.view.component.userBrowser
 * @inherits mad.view.View
 */
var UserBrowser = passbolt.view.component.UserBrowser = mad.view.component.Grid.extend('passbolt.view.component.UserBrowser', /** @static */ {

}, /** @prototype */ {

    /**
     * Insert an Item in the grid.
     *
     * Overrides the default function so we can handle dragging of elements.
     *
     * @param {HTMLElement} item The item to be inserted
     * @param {string} refItemId
     * @param {string} position The position (first or last)
     */
    'insertItem': function (item, refItemId, position) {
        var $row = this._super(item, refItemId, position);
        var $name = $('.js_grid_column_name', $row);
        // Handles draginit event for the current row.
        //$row.on("draginit", function(ev, drag){
        //	// create what we'll drag
        //	var $draggedUser = $('div#js_dragged_user');
        //	if ($draggedUser.length) {
        //		$('.name', $draggedUser).text($name.text());
        //	}
        //	else {
        //		$draggedUser = can.view(
        //			'app/view/template/component/user/draggedUser.ejs', {
        //				name: $name.text()
        //			});
        //		$($draggedUser).appendTo(document.body);
        //		$draggedUser = $('div#js_dragged_user');
        //	}
        //	// indicate we want our mouse on the top-right of it
        //	drag.representative($draggedUser, 0, 0);
        //});
    },

    /**
     * Right click has been detected. (contextual menu).
     * @event item_right_selected
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {bool}
     */
    'tbody tr contextmenu': function (el, ev) {
        ev.stopPropagation();
        ev.preventDefault();

        if (ev.which == 3) {
            var data = null,
                tr = el;
            if (this.getController().getItemClass()) {
                data = tr.data(this.getController().getItemClass().fullName);
            } else {
                data = tr[0].id;
            }
            this.element.trigger('item_right_selected', [data, ev]);
        }
        return false;
    }
});

export default UserBrowser;