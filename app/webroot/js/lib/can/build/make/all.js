steal.config({
	map: {
		"*": {
			"can/util/util.js": "can/util/jquery/jquery.js"
		}
	}
});
steal('can/util', 'can/construct/proxy', 'can/construct/super', 'can/control', 'can/control/plugin',
	'can/view/mustache', 'can/control/view', 'can/view/modifiers', 'can/model', 'can/view/ejs',
	'can/observe/attributes', 'can/observe/delegate', 'can/observe/setter',
	'can/observe/validations', 'can/route', 'can/view/modifiers', 'can/observe/backup');