if(!steal.build){
	steal.build = {};	
}
steal('steal',function( steal ) {
	
	var css = steal.build.css = {};
	/**
	 * 
	 * @param {Object} steals
	 * @param {Object} where
	 */
	css.makePackage = function(steals, where){
		if(!steals || !steals.length){
			return null;
		}
		
		var directory = steal.File(where).dir(),
			srcs = [], codez = [];
		
		steals.forEach(function(stealOpts){
			codez.push(convert(stealOpts.text, stealOpts.id, directory))
			srcs.push(stealOpts.rootSrc+'')
		});
		
		return {
			srcs: srcs,
			code : css.minify(codez.join('\n'))
		}
	}
	//used to convert css referencs in one file so they will make sense from prodLocation
	var convert = function( css, cssLocation, prodLocation ) {
		//how do we go from prod to css
		var cssLoc = new steal.File(cssLocation).dir(),
			newCSS = css.replace(/url\(['"]?([^'"\)]*)['"]?\)/g, function( whole, part ) {

				//check if url is relative
				if (isAbsoluteOrData(part) ) {
					return whole
				}
				//it's a relative path from cssLocation, need to convert to
				// prodLocation
				var rootImagePath = steal.URI(cssLoc).join(part),
					fin = steal.File(prodLocation).pathTo(rootImagePath);
				return "url(" + fin + ")";
			});
		return newCSS;
	},
	isAbsoluteOrData = function( part ) {
		return /^(data:|http:\/\/|https:\/\/|\/)/.test(part)
	},
    calcSavings = function(raw_len, minified_len) {
        var diff_len = raw_len - minified_len, x = Math.pow(10,1);
        return 'Compressed: '+(Math.round((diff_len/raw_len*100)*x)/x)+'%  Before: '+
            string2size(raw_len)+'  After: '+string2size(minified_len);
    },
    string2size = function(bytes) {
        var s = ['bytes','kb','mb','gb','tb','pb'];
        var e = Math.floor(Math.log(bytes)/Math.log(1024));
        return (bytes/Math.pow(1024,Math.floor(e))).toFixed(1)+' '+s[e];
    };
},'steal/build/css/cssminify.js');
