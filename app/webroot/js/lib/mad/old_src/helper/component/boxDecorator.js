steal(
	'jquery/class',
	'jquery/view/ejs',
	'jquery/lang/string',
	'mad/controller/controller.js',
	'mad/view/template/component/decorator/box.ejs'
).then(function () {

	$.String.getObject('mad.helper.component', window, true);
	mad.helper.component.BoxDecorator = {
		'init': function () {
			this.options.loading = true;
			this._super();
		},
		'render': function () {
			if (this.getBoxElement().length) {
				this._super();
				return;
			}

			var html = null;
			// Impossible to get the return value of the decorated function
			// the only way to get it is to pass through an internal variable
			// I would like to understand ...
			//                this._super({'display':false});
			//                html = this.renderedView;

			// The box template
			var boxTemplate = '//' + 'mad/view/template/component/decorator/box.ejs';
			// Insert the box before the component element
			var $box = $($.View(boxTemplate)).insertBefore(this.element);
			// Detach the component element and add it to the box element
			var $element = this.element.detach();
			$box.find('.mad_helper_component_boxDecorator_content').append($element);
			// Render the component
			this._super();
		},

		'getBoxElement': function () {
			if (typeof this.element == 'undefined') {
				throw new Error('The component has to be rendered yet');
			}
			return $(this.element.parents('.mad_helper_component_boxDecorator').get(0));
		}
	};

});