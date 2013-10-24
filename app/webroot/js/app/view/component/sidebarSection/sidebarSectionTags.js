steal(
	'mad/view',
	'app/view/component/sidebarSection.js',
	'app/view/template/component/sidebarSection/sidebarSectionTags.ejs'
).then(function () {

		/*
		 * @class passbolt.view.component.SidebarSection.SidebarSectionTags
		 * @inherits passbolt.view.component.SidebarSection
		 */
		passbolt.view.component.SidebarSection.extend('passbolt.view.component.sidebarSection.SidebarSectionTags', /** @static */ {

		}, /** @prototype */ {

			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */

			/**
			 * Observe when the user clicks on the plus button, to add a tag
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'a.edit-action click': function (el, ev) {
				this.element.trigger('request_tags_edit');
			}

		});
	});