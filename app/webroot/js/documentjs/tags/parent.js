steal(function() {
	return (function() {
		var waiting = {}

		/**
		 * @class DocumentJS.tags.parent
		 * @tag documentation
		 * @parent DocumentJS.tags 
		 * 
		 * Says under which parent the current type should be located.
		 * 
		 * ###Example:
		 * 
		 * @codestart
		 * /**
		 *  * @constructor jQuery.Drag
		 *  * @parent specialevents
		 *  * ...
		 *  *|
		 *  $.Drag = function(){}
		 * @codeend
		 * 
		 * ###End Result:
		 * 
		 * @image jmvc/images/parent_tag_example.png
		 */
		return {
			add: function( line , curData, objects) {
				var m = line.match(/^\s*@parent\s*([\w\.\/\$]*)\s*([\d]*)/)
				var name = m[1],
					inst = objects[name],
					pos = m[2] ? parseInt(m[2]) : null

					if (!inst ) {
						inst = objects[name] = {
							name: name
						}
					}
					if (!this.parents ) {
						this.parents = [];
					}
					this.parents.unshift(inst.name);
					if(pos != null){
						this.order = pos;
					}
					//this.parents.push(inst.name);

				if (!inst.children ) {
					inst.children = [];
				}
				inst.children.push(this.name)
			}
		};

	})();
})
