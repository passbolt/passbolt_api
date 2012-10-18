steal('jquery/dom/fixture').then(function () {

	// create fixtured resource
	var fixturedResources = {};

	// load fixtured resources
	loadFixturedResources = function (category) {
		// create fixtured data
		var catId = category.Category.id;
		fixturedResources[catId] = [];

		var titles = ['facebook', 'jira', 'google', 'jenkins'],
			logins = ['Ced', 'Rems', 'Kev', 'Myri', 'Isma'],
			urls = ['facebook.com', 'passbolt.altasian.com', 'google.com'];

		function getRandomInt(min, max) {
			return Math.floor(Math.random() * (max - min + 1)) + min;
		}

		for(var i = 0; i < 10; i++) {
			fixturedResources[catId].push({
				'Resource': {
					'id': uuid(),
					'category_id': catId,
					'name': titles[getRandomInt(0, titles.length-1)],
					'username': logins[getRandomInt(0, logins.length-1)],
					'uri': urls[getRandomInt(0, urls.length-1)],
					'modified': new Date(),
					'created': new Date(),
					'expiry_date': new Date(),
					'description': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nulla turpis, gravida vitae auctor quis, pretium vitae purus. Aliquam tempor tincidunt porttitor. Suspendisse potenti. '
				}
			});
		}
		//				console.dir
		for(var i in category.children) {
			loadFixturedResources(category.children[i]);
		}
	}

	// resources/viewByCategory fixture
	$.fixture({
		type: 'GET',
		url: APP_URL + '/resources/viewByCategory/{category_id}/{recursive}'
	}, function (original, settings, headers) {
		var content = fixturedResources[original.data.category_id];
		var returnValue = {
			'header': new mad.net.Header({
				'id': uuid(),
				'status': mad.net.Header.STATUS_SUCCESS,
				'title': 'Resource fixture',
				'message': 'Resource fixture for the category ' + original.data.category_id,
				'controller': 'resources',
				'action': 'getCategoryResources'
			}),
			'body': content
		};
		return returnValue;
	});

	// resources/view fixture
	$.fixture({
		type: 'GET',
		url: APP_URL + '/resources/view/{id}'
	}, function (original, settings, headers) {
		var content = null;
		for(var i in fixturedResources) {
			for(var j in fixturedResources[i]) {
				if(fixturedResources[i][j].Resource.id == original.data.id) {
					content = fixturedResources[i][j];
					break;
				}
			}
		}
		var returnValue = {
			'header': new mad.net.Header({
				'id': uuid(),
				'status': mad.net.Header.STATUS_SUCCESS,
				'title': 'Resource fixture',
				'message': 'Resource fixture for the id ' + original.data.id,
				'controller': 'resources',
				'action': 'getCategoryResources'
			}),
			'body': content
		};
		return returnValue;
	});

});