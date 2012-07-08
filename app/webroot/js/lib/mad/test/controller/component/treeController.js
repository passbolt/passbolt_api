steal('funcunit', function(){
	
	// Test environnement, the window of the released popup
	var testEnv = null,
		treeController = null,
		map = null;

	module("mad.controller.component", {
		// runs before each test
		setup: function(){
			S.open('./testEnv/app.html', function(){
				// store the env windows in a global var for the following unit tests
				testEnv = S.win;

				// In test environnement some configuration variables have to be updated
				testEnv.$.jstree._themes = APP_URL+'/js/lib/jstree/themes/';

				// set the map
				map = new testEnv.mad.object.Map({
					'attr.id':{
						'key':	'id',
						'func':	function(value, map){
							return treeController.getId()+'_node_'+value;
						}
					},
					'type':		'icon',
					'data':		'label',
					'children': {
						'key':	'children',
						'func':	testEnv.mad.object.Map.mapObjects
					}
				});
				
				// instantiate a tree
				S('body').append('<div id="tree"/>');
				var $tree = S("#tree");
				treeController = new testEnv.mad.controller.component.TreeController($tree, {
					map:map,
					strategy:'jstree'
				});
				treeController.render();
			});
		},
		// runs after each test
		teardown: function(){
		}
	});

	var data = [
	{
		"id":1,
		"label":"Parent Child 1",
		"action":"#",
		"type":"folder",
		"children":[{
			"id":11,
			"label":"Child 1",
			"action":"#",
			"type":"file",
			"children":[{
				"id":111,
				"label":"Sub Child 1",
				"action":"#",
				"type":"folder"
			}]
		}]
	},
	{
		"id":2,
		"label":"Parent Child 2",
		"action":"#",
		"type":"folder",
		"children":[{
			"id":21,
			"label":"Child 2",
			"action":"#",
			"type":"file",
			"children":[{
				"id":211,
				"label":"Sub Child 2",
				"action":"#",
				"type":"folder"
			}]
		}]
	}
	];

	test('TreeController: Check the instanciation', function(){
		var $tree = S("#tree");
		// bad strategy
		raises(function(){
			treeController = new testEnv.mad.controller.component.TreeController($tree, {
				map:map,
				strategy:'badStrategy'
			});
		}, testEnv.mad.error.WrongParameters, 'The strategy parameter is wrong, it should be one of the followin (jstree, ...)');
		// no map
		raises(function(){
			treeController = new testEnv.mad.controller.component.TreeController($tree, {
				strategy:'jstree'
			});
		}, testEnv.mad.error.MissingOptions, 'The map options is missing');
	});

	test('TreeController: Check the instanciated tree is well the desired component', function(){
		ok(treeController instanceof testEnv.mad.controller.component.TreeController, "The instanciated component is well an instance of the TreeController component Class");
		ok(treeController instanceof testEnv.mad.controller.ComponentController, "The instanciated component is well an instance of the ComponentController Class");
	});

	test('TreeController: Load a node at the root', function(){
		var assertNodesCount = 0,
		nodesCount = 3;
		
		// check if a node is existing in the DOM
		var checkExistingNode = function(object){
			if(S('#tree #tree_node_'+object.id).length){
				assertNodesCount++;
			}
			for(var j in object.children){
				checkExistingNode(object.children[j]);
			}
		}

		//load the node in the jstree and test they are existing in the DOM
		treeController.load(data[0]);
		checkExistingNode(data[0]);
		
		// All the nodes are existing
		equal(assertNodesCount, nodesCount, 'All the nodes are existing');
	});

	test('TreeController: Load multiple nodes at the root', function(){
		var assertNodesCount = 0,
		nodesCount = 6;
		
		// check if a node is existing in the DOM
		var checkExistingNode = function(object){
			if(S('#tree #tree_node_'+object.id).length){
				assertNodesCount++;
			}
			for(var j in object.children){
				checkExistingNode(object.children[j]);
			}
		}

		//load the nodes in the jstree and test they are existing in the DOM
		treeController.load(data);
		for(var i in data){
			checkExistingNode(data[i]);
		}
		
		// All the nodes are existing
		equal(assertNodesCount, nodesCount, 'All the nodes are existing');
	});

});
