steal(
    MAD_ROOT+'/error/callAbstractFunction.js',
    MAD_ROOT+'/error/callInterfaceConstructor.js',
    MAD_ROOT+'/error/callInterfaceFunction.js',
    MAD_ROOT+'/error/callPrivateFunction.js',
    MAD_ROOT+'/error/missingOption.js',
    MAD_ROOT+'/error/noConstructor.js',
    MAD_ROOT+'/error/templateMissing.js',
    MAD_ROOT+'/error/wrongParameters.js'
)
.then(
    function($){
        $.String.getObject('mad.error', null, true);
        mad.error.Error = function(message) {
            this.name = "Error";
            this.message = (message || "An error occured");
        }
        mad.error.Error.prototype = new Error();
    }
);