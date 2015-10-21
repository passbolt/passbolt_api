@hide

Use can.fixture.xhr to create an object that looks like an xhr object.

## Example

The following example shows how the -restCreate fixture uses xhr to return a simulated xhr object:

    "-restCreate" : function( settings, cbType ) {
        switch(cbType) {
            case "success":
                return [
                    {id: parseInt(Math.random()*1000)},
                    "success",
                    can.fixture.xhr()
                ];
            case "complete":
                return [
                    can.fixture.xhr({
                        getResponseHeader: function() {
                            return settings.url+"/"+parseInt(Math.random()*1000);
                        }
                    }),
                    "success"
                ];
        }
    }

@param {Object} [xhr] properties that you want to overwrite
@return {Object} an object that looks like a successful XHR object.
