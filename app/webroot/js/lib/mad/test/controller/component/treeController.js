steal('funcunit', function(){
	
	// Test environnement, the window of the released popup
	var testEnv = null;
	var treeController = null;

	module("controller.component", {
		// runs before each test
		setup: function(){
			S.open('./testEnv.html', function(){
			// store the env windows in a global var for the following unit tests
			testEnv = S.win;

			// In test environnement some configuration variables have to be updated
			testEnv.$.jstree._themes = APP_URL+'/js/lib/jstree/themes/';

			// instantiate a tree
			S('body').append('<div id="tree"/>');
			var $tree = S("#tree");
			treeController = new testEnv.mad.controller.component.TreeController($tree, {map:map});
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

	var map = {
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
			'func':	mapObjects
		}
	};

	test('TreeController: Check the instanciated tree is well the desired component', function(){
		ok(treeController instanceof testEnv.mad.controller.component.TreeController, "The instanciated component is well an instance of the TreeController component Class");
		ok(treeController instanceof testEnv.mad.controller.ComponentController, "The instanciated component is well an instance of the ComponentController Class");
	});

	test('TreeController: Load data', function(){

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

		treeController.load(data);
		
		//load the nodes in the jstree and test they are existing in the DOM
		for(var i in data){
			checkExistingNode(data[i]);
		}
		
		// All the nodes are existing
		equal(assertNodesCount, nodesCount, 'All the nodes are existing');
	});

});
