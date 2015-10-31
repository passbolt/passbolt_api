@property {String} System.stealPath
@parent StealJS.config

Specify the path to the `steal.js` root folder and set many other configuration
values as side effects. This should not typically be called directly.

@option {String}

The folder that contains `steal.js`.

`steal.js` set this internally when loading to setup default paths for
[@dev], [$css], [$less], [@traceur] and [System.baseURL].

   
@body

## Use

`steal.js` calls [System.config] with the path to `steal.js` internally. 
This sets up the following default values in [System.paths]:

 - [@dev] - `PATH/dev.js`
 - [$css] - `PATH/css.js`
 - [$less] - `PATH/less.js`
 - [@traceur] - `PATH/../traceur/traceur.js` - Assumes traceur is in a folder next to steal.
 
Finally, it will set [System.baseURL] provided nothing else is setting it to:

 - `PATH/../..` - if `PATH` ends with _/bower\_components/steal_.
 - `PATH/..` - if `PATH` ends with _/steal_.
 - `PATH` - if the first two conditions are not met.

