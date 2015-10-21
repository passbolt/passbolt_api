import 'mad/model/model';

/**
 * @parent Mad.core_api
 * @inherits mad.Model
 *
 * The aim of the Attribute model is to describe model attributes.
 */
var Attribute = mad.model.Attribute = mad.Model.extend('mad.model.Attribute', /* @static */ {

    attributes: {
        name: 'string',
        type: 'string',
        modelReference: 'object',
        multiple: 'boolean'
    }

}, /** @prototype */ {

    /**
     * Get the associated model reference
     * @return {object}
     */
    getModelReference: function () {
        return this.modelReference;
    },

    /**
     * Get the module attribute name
     * @return {string}
     */
    getName: function() {
        return this.name;
    },

    /**
     * Is the attribute multiple
     * @return {boolean}
     */
    isMultiple: function() {
        return this.multiple;
    }

});

export default Attribute;
