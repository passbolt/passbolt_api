steal( 
	MAD_ROOT+'/core/singleton.js'
	)
.then( 
	function($){
        
		/*
        * @class mad.object.Map
        * @parent index
		* @inherits $.Class
		* 
        * The ajax wrapper is an interface to the jQuery ajax function. It allows 
        * developpers to make their ajax request, moreover it allows them to make 
        * ajax transactions to minimize server calls by aggregating ajax requests 
        * 
        * @constructor
        * Creates a new ajax wrapper
        * @return {mad.object.Map}
        */
		$.Class('mad.object.Map', 
                
		/** @static */
        
		{
				'mapObject': function(object, map){
					return map.mapObject(object);
				},
				'mapObjects': function(arr, map){
					return map.mapObjects(arr);
				}
			},
        
			/** @prototype */
			{
				'init': function(map)
				{
					this.map = map;
				},
			
				'mapObject': function(object)
				{
					var returnValue = {};

					for(var key in this.map){
						var mapKeyElts = key.split('.'),			// the map keys (targetKey || targetKeyLvl1.targetKeyLvl2)
							current = returnValue,					// the current position in the final object
							position = 0;							// position of the cursors in the mapKeyElts

						// foreach mapKeyElts we add add a level in the final object
						// at the leaf we insert the value
						for(var i in mapKeyElts){
							var mapKeyElt = mapKeyElts[i];

							// if the leaf is reached
							if(position == mapKeyElts.length-1){

								// if a transformation func is given
								if(typeof this.map[key] == 'object'){
									var func = this.map[key].func;
									var keyToMap = this.map[key].key;
									// @todo what to do if the key to map does not exist
									if(typeof object[keyToMap] != 'undefined'){
										current[mapKeyElt] = func(object[keyToMap], this);
									}
								}
								else{
									// @todo what to do if the key to map does not exist
									if(typeof object[this.map[key]] != 'undefined'){
										current[mapKeyElt] = object[this.map[key]];
									}
								}

							}
							// else we move the cursor in the mapKeyElts
							else{
								if(typeof current[mapKeyElt] == 'undefined')
									current[mapKeyElt] = [];
								current = current[mapKeyElt];
							}

							position++;
						}
					}

					return returnValue;
				},
			
				'mapObjects': function(arr)
				{	
					if(!($.isArray(arr))){
						throw new mad.error.WrongParameters('The function mapObjects is expecting an array as first parameter');
					}
					var returnValue = [];
					for(var i in arr){
						returnValue[i] = this.mapObject(arr[i]);
					}
					return returnValue;
				}
			});
        
	}
	);
