steal(
	// Handle drag events.
	'jquery/event/drag',
	// Grid component.
	'mad/view/component/grid.js',
	// Dragged Resource template.
	'app/view/template/component/user/draggedUser.ejs'
).then(function () {

		/*
		 * @class passbolt.view.component.userBrowser
		 * @inherits mad.view.View
		 */
		mad.view.component.Grid.extend('passbolt.view.component.userBrowser', /** @static */ {

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
			}
		});
	});