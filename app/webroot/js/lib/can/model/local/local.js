steal('can/model', 'jquery/lang/object', function(){

can.Model('can.Model.Local',{

	compare : {},

	identifier:function(){
		return parseInt(100000 * Math.random());
	},

	localStore: function(cb){
		var name = this.shortName,
			data = $.evalJSON( window.localStorage[name] || (window.localStorage[name] = "{}") ),
			res = cb.call(this, data);

		if(res !== false){
			window.localStorage[name] = $.toJSON(data);
		}
	},
	
	findAll: function(params, success){
		var instances = [],
			def = $.Deferred().then(success);

		this.localStore(function(objs){
			for(var o in objs){
				if($.Object.same(objs[o], params, this.compare)){
					instances.push(new this(objs[o]));
				}
			}
		});

		return def.resolve(instances);
	},

	findOne: function(id , success){
		var idProp = this.id,
			def = $.Deferred().then(success),
			one = undefined;

		this.localStore(function(objs){
			for(var o in objs){
				if(objs[o][idProp] == id){
					one = new this(objs[id]);
					break;
				}
			}
		});

		return def.resolve(one);
	},

	destroyAll: function(params, success){
		var def = $.Deferred().then(success);

		this.localStore(function(objs){
			$.each(params, function(){
				delete objs[this]
			});
		});
		
		return def.resolve({});
	},

	destroy: function(id, success){
		return this.destroyAll([id], success);
	},
	
	create: function(attrs, success){
		var idProp = this.id,
			def = $.Deferred().then(success);

		this.localStore(function(objs){
			attrs[idProp] = attrs[idProp] || this.identifier();
			objs[attrs[idProp]] = attrs;
		});

		return def.resolve(attrs);
	}

})

});