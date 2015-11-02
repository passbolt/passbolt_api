<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
	<head>
		<title>Form Controller Example</title>
		<script type='text/javascript' src='../../steal/steal.js'></script>
		<link rel="stylesheet" type="text/css" href="../../../../../../css/grid.css">
		<link rel="stylesheet" type="text/css" href="../../../../../../css/reset.css">
		<link rel="stylesheet" type="text/css" href="../../../../../../css/icons.css">
		<link rel="stylesheet" type="text/css" href="../../../../../../css/tree.css">
		<style>
			body {
				height:100%;
				margin:0;
				padding:0;
			}
			iframe {
				width:100%;
				height:100%;
				margin:0;
				padding:0;
			}
		</style>
	</head>
	<body>
		
		<div id="demo-html">
			<div id="js_demo_app_controller">
				<ul id="js_list" class="sidebar left"></ul>
				<div id="js_demo_container" class="content right"></div>
<!--				<iframe id="js_demo" src="" />-->
			</div>
		</div>

<script type="text/javascript">

steal(
	'mad/demo/init.js'
).then(function() {
		var demos = mad.model.Model.models([
			{ 'id': 'form', 'label': 'form',
				'children': [
					{ 'id': 'form_controller', 'label': 'form controller', 'url': './form/formcontroller.html' }
				]
			}
		]);
		var lists = new mad.controller.component.DynamicTreeController('#js_list', {
			'map': new mad.object.Map({
				'id': 'id',
				'label': 'label',
				'children': {
					'key': 'children',
					'func': mad.object.Map.mapObjects
				}
			}),
			'itemClass': mad.model.Model,
			'callbacks': {
				'itemSelected': function (el, ev, item) {
					var demo = '<iframe src="' + item.url + '"></iframe>';
					$('#js_demo_container').html(demo);
				}
			}
		});
		lists.render();
		lists.load(demos);
});

</script>
												
	</body>
</html>