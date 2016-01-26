import 'mad/view/component/grid';
import 'app/view/template/component/user/dragged_user.ejs!';

/*
 * @class passbolt.view.component.userBrowser
 * @inherits mad.view.View
 */
var UserBrowser = passbolt.view.component.UserBrowser = mad.view.component.Grid.extend('passbolt.view.component.UserBrowser', /** @static */ {

}, /** @prototype */ {

    /**
     * Right click has been detected. (contextual menu).
	 * we just stop the event here, as we do not want to base our contextual menu on this event, but on the mouse down event instead.
     * @event item_right_selected
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @return {bool}
     */
    'tbody tr contextmenu': function (el, ev) {
        ev.stopPropagation();
        ev.preventDefault();
        return false;
    },

	/**
	 * Right click has been detected. (contextual menu).
	 * @event item_right_selected
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 * @return {bool}
	 */
	'tbody tr mousedown': function (el, ev) {
		var self = this;
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
			// We trigger the event item_right_selected, and
			// we use setTimeout 0 to do it at the end of the execution thread.
			setTimeout(
				function() {
					self.element.trigger('item_right_selected', [data, ev]);
				},
				0
			);

		}
		return false;
	}
});

export default UserBrowser;