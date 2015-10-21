import mad from 'mad/util/util';

// Initialize the helper namespaces.
mad.helper = mad.helper || {};

/**
 * @parent Mad.core_helper_api
 * @inherits can.Construct
 *
 * A set of tools to help developer with Components.
 */
var ComponentHelper = mad.helper.Component = can.Construct.extend('mad.helper.Component', /** @static */ {

    /**
     * A factory to create and insert components.
     *
     * @param {HTMLElement} refElement The reference element to position the component with.
     * @param {string} position The position of the content to insert, regarding the reference element.
     * For a complete list of available values see the [mad.helper.Html HTML Helper]
     * @param {mad.Component} ComponentClass The component class
     * @param {array} options The options to pass to the component class constructor
     *
     * @return {mad.Component}
     */
    create: function (refElement, position, ComponentClass, options) {
        // The HTML Element that will carry the component.
        var $component = null,
        // The Html that will be used to render the HTML Element.
            html = '',
        // The options to pass to the Component Class constructor.
            options = options || {},
        // Id of the component, if defined.
            id = (options.id || '');

        // class attributes options.
        var classAttributes = {};
        if (typeof ComponentClass.defaults.attributes != 'undefined') {
            classAttributes = ComponentClass.defaults.attributes;
        }
        // attributes to add to the tag
        var attributes = $.extend({}, classAttributes, options.attributes);

        if (refElement.length == 0) {
            throw mad.Exception.get(mad.error.WRONG_PARAMETER, 'refElement');
        }

        // Construct the html based on the component options.
        var tag = options.tag || ComponentClass.defaults.tag;
        html = '<' + tag + ' id="' + (options.id || '') + '"';

        // Add attributes to the generated html.
        for (var attrName in attributes) {
            html += ' ' + attrName + '="' + attributes[attrName] + '"';
        }

        // End of the component html construction.
        html += '/>';

        // Insert the HTML Element which will carry the component in the DOM.
        $component = mad.helper.Html.create(refElement, position, html);

        // Instantiate the component and return it.
        return new ComponentClass($component, options);
    }

}, /* @prototype */ {});

export default ComponentHelper;
