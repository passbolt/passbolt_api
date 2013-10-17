steal(
    'mad/form/formController.js',
	'app/model/itemTag.js',
    'app/view/template/form/tag/editForm.ejs'
).then(function () {

        /**
         * @class passbolt.controller.form.resource.CreateFormController
         * @inherits {mad.form.FormController}
         * @parent index
         *
         * @constructor
         * Instanciate a Resource Create Form Controller
         *
         * @param {HTMLElement} element the element this instance operates on.
         * @param {Object} [options] option values for the controller.  These get added to
         * this.options and merged with defaults static variable
         * @return {passbolt.controller.form.resource.CreateFormController}
         */
        mad.form.FormController.extend('passbolt.controller.form.tag.EditFormController', /** @static */ {
            'defaults': {
                'templateBased': true,
                /**
                 * the foreign Model on which to plug the comments system
                 */
                'foreignModel' : null,
                /**
                 * The foreign id where to plug the new comments
                 */
                'foreignId'		 : null
            }
        }, /** @prototype */ {

            /**
             * After start hook.
             * Create the form elements
             *
             * @return {void}
             */
            'afterStart': function () {
				// foreign_model hidden field
				this.addElement(
					new mad.form.element.TextboxController($('.tag_foreign_model', this.element), {
						modelReference: 'passbolt.model.ItemTag.foreign_model'
					}).start().setValue(this.options.foreignModel)
				);

				// foreign_id hidden field
				this.addElement(
					new mad.form.element.TextboxController($('.tag_foreign_id', this.element), {
						modelReference: 'passbolt.model.ItemTag.foreign_id'
					}).start().setValue(this.options.foreignId)
				);


                this.tagList = this.addElement(
                    new mad.form.element.TextboxController($('.tag_list', this.element), {
                        modelReference: 'passbolt.model.ItemTag.tag_list'
                    }).start()
                );
            },

			'setTags': function(tags) {
				var tagsStr = "";
				tags.each(function(tag){
					tagsStr += (tag.Tag.name + ", ");
				});
				this.tagList.setValue(tagsStr);
			}

            /* ************************************************************** */
            /* LISTEN TO THE VIEW EVENTS */
            /* ************************************************************** */



        });
    });