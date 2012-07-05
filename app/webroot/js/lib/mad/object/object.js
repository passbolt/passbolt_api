mapObject = function(object, map)
	{
		var returnValue = {};
	
		for(var key in map){
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
					if(typeof map[key] == 'object'){
						var func = map[key].func;
						var keyToMap = map[key].key;
						current[mapKeyElt] = func(object[keyToMap], map);
					}
					else{
						current[mapKeyElt] = object[map[key]];
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
	}
	
mapObjects = function(arr, map)
{
	var returnValue = [];
	for(var i in arr){
		returnValue[i] = mapObject(arr[i], map);
	}
	return returnValue;
}