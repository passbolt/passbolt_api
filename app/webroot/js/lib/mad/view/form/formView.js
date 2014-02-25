steal(
	'mad/view'
).then(function () {

	/*
	 * @class mad.view.form.FormView
	 * @inherits mad.view.View
	 * @hide
	 * 
	 * @constructor
	 * 
	 * @return {mad.view.form.FormView}
	 */
	mad.view.View.extend('mad.view.form.FormView', /** @static */ {

	}, /** @prototype */ {

		'setElementState': function (element, state) {
			// elt's id
			var eltId = element.getId(),
				$label = $('label[for="' + eltId + '"]'),
				$wrapper = element.element.parent('.js_form_element_wrapper');

			switch (state) {
				case 'success':
					if ($label) {
						$label.removeClass('error');
					}
					if ($wrapper) {
						$wrapper.removeClass('error');
					}
				break;
				case 'error':
					if ($label) {
						$label.addClass('error');
					}
					if ($wrapper) {
						$wrapper.addClass('error');
					}
				break;
			}
		}

	});
});