steal(
	// Handle drag events.
	'jquery/event/drag',
	// Grid component.
	'mad/view/component/grid.js',
	// Dragged Resource template.
	'app/view/template/component/resource/draggedResource.ejs'
).then(function () {

		/*
		 * @class passbolt.view.component.passwordBrowser
		 * @inherits mad.view.View
		 */
		mad.view.component.Grid.extend('passbolt.view.component.passwordBrowser', /** @static */ {

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
			}
		});
	});