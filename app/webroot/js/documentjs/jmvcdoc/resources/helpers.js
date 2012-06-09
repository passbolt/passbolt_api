var orderedParams = function( params ) {
	var ordered = [];
	for ( var name in params ) {
		ordered[params[name].order] = params[name]
	}
	return ordered;
}

DocumentationHelpers = {
	previousIndent: 0,
	calculateDisplay: function( previous, current ) {

		var t = current.split(/\./)
		var p = previous.split(/\./);
		var left_res = [],
			right_res = []
			for ( var j = 0; j < t.length; j++ ) {
				if ( p[j] && p[j] == t[j] ) left_res.push(t[j])
				else {
					//put everything else in right res
					right_res = t.slice(j);
					break;
				}
			}
			if ( left_res.length == 1 && (left_res[0] == "jQuery" || left_res[0] == "steal") ) return {
				length: 1,
				name: current
			}

			if ( this.indentAdjust === undefined ) this.indentAdjust = !! (left_res.length) ? 0 : 1;
		var newIndent = left_res.length < 2 ? left_res.length + this.indentAdjust : left_res.length;

		return {
			length: newIndent,
			name: right_res.join(".")
		}
	},
	normalizeName: function( name ) {
		return name.replace(/&gt;/, "_gt_").replace(/\*/g, "_star_");
	},
	linkTags: function( tags ) {
		var res = [];
		for ( var i = 0; i < tags.length; i++ )
		res.push("<a href='#&search=" + tags[i] + "'>" + tags[i] + "</a>")
		return res.join(" ");
	},
	linkOpen: function( addr ) {
		return "<a href='#&who=" + addr + "'>" + addr + "</a>"
	},
	signiture: function() {
		var res = [],
			name = this._data.name;
		//we should check if prototype or static is available
		name = name.replace("jQuery.", "$.")

		var stat = name.lastIndexOf('.static.')
		var prto = name.lastIndexOf('.prototype.')
		if ( stat != -1 ) {
			name = name.substring(0, stat) + "." + name.substring(stat + 8);
		} else if ( prto != -1 ) {
			name = jQuery.String.underscore(name.substring(0, prto).replace("$.", "")) + "." + name.substring(prto + 11);
		}

		if (this._data.construct) {
			name = "new " + name;
		}
		var ordered = orderedParams(this._data.params);
		for ( var n = 0; n < ordered.length; n++ ) {
			res.push(ordered[n].name)
		}


		return name + "(" + res.join(", ") + ")" + (this._data.ret ? " -> " + this._data.ret.type : "");
	},
	link: function( content, dontReplace ) {
		return content.replace(/\[\s*((?:['"][^"']*["'])|[^\|\]\s]*)\s*\|?\s*([^\]]*)\s*\]/g, function( match, first, n ) {
			//need to get last
			//need to remove trailing whitespace
			if (/^["']/.test(first) ) {
				first = first.substr(1, first.length - 2)
			}
			if ( /^\/\//.test(first) ) {
				first = steal.root.join(first.substr(2))
			}
			var url = Doc.findOne({name: first}) || null;
			if(!url){
				//try again ...
				// might start w/ jQuery
				var convert = first;
				if(first.indexOf('$.') == 0){
					convert = "jQuery."+convert.substr(2);
					url = Doc.findOne({name: convert}) || null;
				}
				if(!url && first.indexOf('::')){
					url = Doc.findOne({name: convert.replace('::',".prototype.")}) || null;
				}
				if(!url){
					var parts = convert.split('.')
					parts.splice(parts.length-1,0,"static");
					url = Doc.findOne({name: parts.join('.')}) || null;
				}
			}
			
			
			if ( url ) {
				if (!n ) {
					n = dontReplace ? first : first.replace(/\\.static/, "")
				}
				return  "<a href='" +$.route.url({who:  url.name }) + "'>" + n + "</a>"
			} else if ( typeof first == 'string' && first.match(/^https?|www\.|#/) ) {
				return "<a href='" + first + "'>" + (n || first) + "</a>"
			}
			return match;
		})
	},

	shortenUrl: function( url ) {
		url = url.href ? url.href : url;
		var parts = url.match(/(https?:\/\/|file:\/\/)[^\/]*\/(.*)/);
		return url = parts[2] ? parts[2] : url;
	},
	source : function(comment){
		var matches = comment.src.match(/([^\/]+)\/(.+)/);
		return DOCS_SRC_MAP[matches[1]]+"/blob/master/"+matches[2]+"#L"+comment.line
	}
}