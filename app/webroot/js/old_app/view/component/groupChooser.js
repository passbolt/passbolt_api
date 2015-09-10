steal(
	'jquery/event/drop',
	'mad/view/component/tree/dynamicTree.js'
).then(function () {

		/*
		 * @class passbolt.view.component.groupChooser
		 * @inherits mad.view.View
		 */
		mad.view.component.tree.DynamicTree.extend('passbolt.view.component.groupChooser', /** @static */ {

		}, /** @prototype */ {

			/**
			 * Insert an Item in a tree.
			 *
			 * Overrides the default function so we can also handle drop events.
			 *
			 * @param {HTMLElement} item The item to be inserted
			 * @param {string} refItemId
			 * @param {string} position The position (first or last)
			 */
			'insertItem': function(item, refItemId, position) {
				var self = this;
				// Get current category.
				var group = this._super(item, refItemId, position);
				// Bind the row with drop events.
				$("> .row", $(group))
					// Dropover event.
					.bind("dropover", function(ev, drop, drag) {
						$(this).addClass("drop-over");
						self.element.trigger('group_dropover', [drop, drag, ev]);
					})
					// Dropout event.
					.bind("dropout", function(ev, drop, drag) {
						$(this).removeClass("drop-over");
						self.element.trigger('group_dropout', [drop, drag, ev]);
					})
					.bind("dropon", function(ev, drop, drag) {
						$(this).removeClass("drop-over");
						self.element.trigger('group_dropon', [drop, drag, ev]);
					});
			}
		});
	});