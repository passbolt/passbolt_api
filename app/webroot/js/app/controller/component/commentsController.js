steal(
		'app/model/comment.js',
		'app/view/component/comments.js',
		'app/controller/component/commentsListController.js'
).then(function () {

        /*
         * @class passbolt.controller.CommentsController
         * @inherits mad.controller.component.ComponentController
         * @parent index
         *
         * @constructor
         * Creates a new Comments controller
         *
         * @param {HTMLElement} element the element this instance operates on.
         * @param {Object} [options] option values for the controller.  These get added to
         * this.options and merged with defaults static variable
         * @return {passbolt.controller.CommentsController}
         */
				mad.controller.ComponentController.extend('passbolt.controller.component.CommentsController', /** @static */ {
					'defaults': {
						'label': 'Comments Controller',
						'viewClass': passbolt.view.component.Comments,
						// the resource to bind the component on
						'resource': this.options.resource
					}

        }, /** @prototype */ {

					/**
					 * Called right after the start function
					 * @return {void}
					 * @see {mad.controller.ComponentController}
					 */
					'afterStart': function() {
						// Instantiate the comments List controller
						var commentsListController = new passbolt.controller.component.CommentsListController($('#js_rs_details_comments_list', this.element), {
						 'resource': this.options.resource
						 });
						 commentsListController.start();

						// create a form to add a comment
						this.addFormController = new mad.form.FormController($('#js_rs_details_comments_add_form', this.element), {
							'templateBased': true,
							'templateUri': 'app/view/template/form/comment/addForm.ejs'
						});
						this.addFormController.start();
						// Hide the comment add form by default
						this.addFormController.setState('hidden');

						// Add resource id hidden field
						this.addFormController.addElement(
							new mad.form.element.TextboxController($('#js_rs_details_comment_content', this.element), {
								modelReference: 'passbolt.model.Comment.content'
							}).start()
						);
					}
					
        });

    });