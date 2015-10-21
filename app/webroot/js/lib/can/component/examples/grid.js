can.Component.extend({
	tag: 'grid',
	viewModel: {
		items: []
	},
	template: '<table><tbody><content></content></tbody></table>',
	events: {
		init: function () {
			this.update();
		},
		'{deferreddata} change': 'update',
		update: function () {
			var deferred = this.viewModel.attr('deferreddata'),
				viewModel = this.viewModel;
			if (can.isDeferred(deferred)) {
				this.element.find('tbody')
					.css('opacity', 0.5);
				deferred.then(function (items) {
					viewModel.attr('items')
						.attr(items, true);
				});
			} else {
				viewModel.attr('items')
					.attr(deferred, true);
			}
		},
		'{items} change': function () {
			this.element.find('tbody')
				.css('opacity', 1);
		}
	}
});
