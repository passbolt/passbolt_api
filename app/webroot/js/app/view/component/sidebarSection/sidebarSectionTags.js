steal(
	'mad/view',
	'app/view/component/sidebarSection.js',
	'app/view/template/component/sidebarSection/sidebarSectionTags.ejs'
).then(function () {

		/*
		 * @class passbolt.view.component.Comments
		 * @inherits mad.view.View
		 */
		passbolt.view.component.SidebarSection.extend('passbolt.view.component.SidebarSection.SidebarSectionTags', /** @static */ {

		}, /** @prototype */ {

			'init': function (el, options) {
				this._super(el, options);
			},

			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */

			/**
			 * Observe when the user clicks on the plus button, to add a comment
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'a.edit-action click': function (el, ev) {
                this.controller.toggleEdit();
			}

		});
	});