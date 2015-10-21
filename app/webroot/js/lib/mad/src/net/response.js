import 'mad/model/model';

/**
 * @inherits mad.model.Model
 * @parent mad.net
 *
 * Our ajax response model
 *
 * @constructor
 * Creates a new ajax response
 * @return {mad.net.Response}
 */
var Response = mad.Model.extend('mad.net.Response', /** @static */ {

    attributes: {
        /**
         * Passbolt response Header
         * @type {object}
         */
        'header': 'json',
        /**
         * Passbolt response Body
         * @type {mixed}
         */
        'body': 'json'
    },

    /**
     * Status error
     * @type {string}
     */
    'STATUS_ERROR': 'error',
    /**
     * Status notice
     * @type {string}
     */
    'STATUS_NOTICE': 'notice',
    /**
     * Status success
     * @type {string}
     */
    'STATUS_SUCCESS': 'success',
    /**
     * Status warning
     * @type {string}
     */
    'STATUS_WARNING': 'warning',

    /**
     * Not response id defined
     * @type {string}
     */
    'RESPONSE_ID_UNDEFINED': 'undefined',
    /**
     * Not response controller defined
     * @type {string}
     */
    'RESPONSE_CONTROLLER_UNDEFINED': 'undefined',
    /**
     * Not response action defined
     * @type {string}
     */
    'RESPONSE_ACTION_UNDEFINED': 'undefined',

    /**
     * Response function factory. Build a response function of a predefined type
     * the response factory is able to build
     * @param {string} type The desired type of response (server
     * @param {object} data The server response
     * @return {mad.net.Response}
     */
    getResponse: function (type, data) {
        var returnValue = null,
            header = {},
            body = null;

        switch (type) {
            case 'unreachable':
                header = {
                    'id': mad.net.Response.RESPONSE_ID_UNDEFINED,
                    'status': mad.net.Response.STATUS_ERROR,
                    'controller': mad.net.Response.RESPONSE_CONTROLLER_UNDEFINED,
                    'action': mad.net.Response.RESPONSE_ACTION_UNDEFINED,
                    'title': __('Unable to reach the server'),
                    'message': __('The url is probably incorrectly formatted')
                };
                body = data;
            break;
        }

        // build the response to return
        returnValue = new mad.net.Response({
            header: header,
            body: body
        });
        return returnValue;
    },

    /**
     * Check that bulk data is formated as a ajax server response
     * @return {boolean}
     */
    isResponse: function (data) {
        var returnValue = false;

        if(typeof data != 'undefined' && data != null) {
            if (data.header && data.body) {
                returnValue = true
            }
        }

        return returnValue;
    },

    /**
     * Get data in an ajax server response
     * @return {mixed} The meaningfull server response data
     */
    getData: function (data) {
        return data.body;
    }

}, /** @prototype */ {

    /**
     * Get the response' status
     * @return {string}
     */
    getStatus: function () {
        return this.attr('header').status;
    },

    /**
     * Get the response' title
     * @return {string}
     */
    getTitle: function () {
        return this.attr('header').title;
    },

    /**
     * Get the response' message
     * @return {string}
     */
    getMessage: function () {
        return this.attr('header').message;
    },

    /**
     * Get the response' action
     * @return {string}
     */
    getAction: function () {
        return this.attr('header').action;
    },

    /**
     * Get the response' controller
     * @return {string}
     */
    getController: function () {
        return this.attr('header').controller;
    },

    /**
     * Get the response' data
     * @return {string}
     */
    getData: function () {
        return this.attr('body');
    }
});

export default Response;
