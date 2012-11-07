steal('./can.js',function(can){

	// fragment.js
	// ---------
	// _DOM Fragment support._
	
	var table = document.createElement('table'),
		tableRow = document.createElement('tr'),
		containers = {
		  'tr': document.createElement('tbody'),
		  'tbody': table, 'thead': table, 'tfoot': table,
		  'td': tableRow, 'th': tableRow,
		  '*': document.createElement('div')
		},
		fragmentRE = /^\s*<(\w+)[^>]*>/,
		fragment  = function(html, name) {
			if (name === undefined) {
				name = fragmentRE.test(html) && RegExp.$1;
			}
			if (!(name in containers)) name = '*';
			var container = containers[name];
			// IE's parser will strip any `<tr><td>` tags when `innerHTML`
			// is called on a `tbody`. To get around this, we construct a 
			// valid table with a `tbody` that has the `innerHTML` we want. 
			// Then the container is the `firstChild` of the `tbody`.
			// [source](http://www.ericvasilik.com/2006/07/code-karma.html).
			if(name === "tr") {
				var temp = document.createElement('div');
				temp.innerHTML = "<table><tbody>" + html + "</tbody></table>";
				container = temp.firstChild.firstChild;
			} else {
				container.innerHTML = '' + html;
			}
			// IE8 barfs if you pass slice a `childNodes` object, so make a copy.
			var tmp = {},
				children = container.childNodes;
			tmp.length = children.length;
			for(var i=0; i<children.length; i++){
				tmp[i] = children[i];
			}
			return [].slice.call(tmp);
		}
	
	can.buildFragment = function(html, nodes){
		var parts = fragment(html),
			frag = document.createDocumentFragment();
		parts.forEach(function(part){
			frag.appendChild(part);
		})
		return frag;
	};
	
})
