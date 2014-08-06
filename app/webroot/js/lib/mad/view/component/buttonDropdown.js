steal(
	'mad/view'
).then(function () {

		/*
		 * @class mad.view.component.ButtonDropdown
		 * @inherits mad.view.View
		 */
		mad.view.View.extend('mad.view.component.ButtonDropdown', /** @static */ {

			'defaults': {
				/**
				 * main clickable element.
				 */
				button: null,
				/**
				 * dropdown element (wrapper).
				 */
				dropdown: null,
				/**
				 * content of dropdown (sublinks)
				 */
				content: null
			}

		}, /** @prototype */ {

			'init': function (el, options) {
				// Construct parent.
				this._super(el, options);
				// Init elements.
				this.options.button = this.element;
				this.options.dropdown = this.options.button.closest('.dropdown');
				if (this.options.button.attr('data-dropdown-content-id') == undefined) {
					this.options.content = this.options.button.next();
				} else {
					this.options.content = $("#" + this.options.button.attr('data-dropdown-content-id'));
				}
			},

			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */

			/**
			 * Listen to the event click on the DOM button element
			 * @return {void}
			 */
			'click': function (el, ev) {
				this.options.dropdown.toggleClass('pressed');
				if(this.options.dropdown.hasClass('pressed')) {
					this.options.content.addClass('visible');
				} else {
					this.options.content.removeClass('visible');
				}

				return false;
			},

			/**
			 * Intercept global click event.
			 *
			 * Intercept global click event and close menu if open.
			 *
			 * @param el
			 * @param ev
			 */
			'{document} click': function (el, ev) {
				if (!this.element.is(el)) {
					this.options.content.removeClass('visible');
					this.options.dropdown.removeClass('pressed');
				}
			}

		});
	});