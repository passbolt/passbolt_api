steal.loadedProductionCSS = true;
steal('can/util/string',
	'can/view/modifiers',
	'can/view/ejs',
    'documentjs/jmvcdoc/models/search.js',
	'documentjs/jmvcdoc/content',
	'documentjs/jmvcdoc/nav',
	'documentjs/jmvcdoc/search',
	'can/route',
	'steal/html',
	'steal/less'
).then('./style.less', function () {
	var pageNameArr = window.location.href.match(/docs\/(.*)\.html/),
		pageName = pageNameArr && pageNameArr[1];

	if (pageName && location.hash == "") {
		window.location.hash = "&who=" + pageName
	}
	can.route(":who", {who : "index"})("/search/:search");


	new Jmvcdoc.Nav('#nav');
	new Jmvcdoc.Content("#doc",{clientState : can.route.data});
	new Jmvcdoc.Search("#search");
});
