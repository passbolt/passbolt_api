//import 'jquery/event/drag';
import 'mad/view/component/grid';
import 'app/view/template/component/resource/dragged_resource.ejs!';


/*
 * @inherits mad.view.Grid
 */
var PasswordBrowser = passbolt.view.component.PasswordBrowser = mad.view.component.Grid.extend('passbolt.view.component.PasswordBrowser', /** @static */ {

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
		//	var $draggedResource = $('div#js_dragged_resource');
		//	if ($draggedResource.length) {
		//		$('.name', $draggedResource).text($name.text());
		//	}
		//	else {
		//		$draggedResource = can.view(
		//			'app/view/template/component/resource/draggedResource.ejs', {
		//				name: $name.text()
		//			});
		//		$($draggedResource).appendTo(document.body);
		//		$draggedResource = $('div#js_dragged_resource');
		//	}
		//	// indicate we want our mouse on the top-right of it
		//	drag.representative($draggedResource, 0, 0);
		//});
	},

	/**
	 * Click on a password element.
	 * @event password_clicked
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @return {bool}
	 */
	'tbody tr td.password a click': function (el, ev) {
		ev.stopPropagation();
		ev.preventDefault();
		var data = null,
			tr = $(el).parents('tr');
		if (this.getController().getItemClass()) {
			data = tr.data(this.getController().getItemClass().fullName);
		} else {
			data = tr[0].id;
		}
		this.element.trigger('password_clicked', [data, ev]);
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

export default PasswordBrowser;