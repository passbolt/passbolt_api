steal(
	'mad/view',
	'app/view/component/sidebarSection.js',
	'app/view/template/component/sidebarSection/sidebarSectionDescription.ejs'
).then(function () {

		/*
		 * @class passbolt.view.component.SidebarSection.SidebarSectionDescription
		 * @inherits passbolt.view.component.SidebarSection
		 */
		passbolt.view.component.SidebarSection.extend('passbolt.view.component.sidebarSection.SidebarSectionDescription', /** @static */ {

		}, /** @prototype */ {

			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */

			/**
			 * Observe when the user clicks on the edit button, or the description content, to modify the description content
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'a#js_edit_description_button, p.description_content click': function (el, ev) {
				this.element.trigger('request_resource_description_edit');
			},

			/**
			 * Set the visibility of the description
			 *
			 * @param {boolean} visible Whether it is visible
			 */
			'showDescription': function(visible) {
				if(visible) {
					$('.description_content', $(this.element)).show();
				}
				else {
					$('.description_content', $(this.element)).hide();
				}
			}

		});
	});
