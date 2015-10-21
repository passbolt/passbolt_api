@typedef {function(Number,String,Object.<typeName,*>,headers)} can.fixture.types.responseHandler(status,statusText,responses,headers) responseHandler
@parent can.fixture.types

@description Specifies the response of an AJAX request.

@signature `response(status [,statusText], responses, headers)`

Specify a HTTP response.

@param {Number} status

The [HTTP response code](http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html). Ex: `200`.

@param {String} [statusText] the status text of the response. Ex: "success"
for 200.

@param {Object.<typeName,*>} responses An object of responses by type.  For example:

    {
      text: "{\"age\":5}"
    }

If responses does not have a `typeName` property for the type of request, the
entire responses object is used as the response data. 

@param {Object.<headerName,String>} headers HTTP response headers and values.

@signature `response(responses)`

Specify the body of a successful HTTP response.


@param {Object.<typeName,*>} responses An object of responses by type.  For example:

    {
      text: "{\"age\":5}"
    }

If responses does not have a `typeName` property for the type of request, the
entire responses object is used as the response data. 
