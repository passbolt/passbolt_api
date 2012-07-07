steal('funcunit', function(){

	module("lang", {
		// runs before each test
		setup: function(){
		},
		// runs after each test
		teardown: function(){
		}
	});	
	
	// Sample of dictionnary
	var dico = {
		'my sentence without hook':'ma phrase sans hook',
		'my sentence with a final hook %s':'ma phrase avec un hook final %s',
		'%s my sentence with a start hook':'%s ma phrase avec un hook en debut',
		'%s my sentence with a start and a final hooks %s':'%s ma phrase avec un hook en debut et en fin %s',
		'%s':'%s'
	};

	// Fixture to access to the dictionnary
	// not required but good to know how to use it
	$.fixture({
		type: 'post',  
		url: APP_URL+'/lg/jsDictionnary'
	},
	function(settings){
		return dico;
	});

	test('I18n : Load dictionnary', function(){
		stop();
		mad.net.Ajax.singleton().request({
			'type':         'post',
			'url':          APP_URL+'/lg/jsDictionnary',
			'async':        false,
			'dataType':     'json',
			'success':      function(DATA){
				var i18n = mad.lang.I18n.singleton();
				i18n.loadDico(DATA);
				for(var key in i18n.dico){
					equal(dico[key], i18n.dico[key]);
					start();
				}
			}
		});
	});

	test('I18n : translate', function(){
		stop();
		mad.net.Ajax.singleton().request({
			'type':         'post',
			'url':          APP_URL+'/lg/jsDictionnary',
			'async':        false,
			'dataType':     'json',
			'success':      function(DATA){
				var i18n = mad.lang.I18n.singleton();
				i18n.loadDico(DATA);

				equal(i18n.translate('my sentence without hook'), 'ma phrase sans hook');
				equal(__('my sentence without hook'), 'ma phrase sans hook');
				equal(i18n.translate('my sentence with a final hook %s', ['HOOK_FINAL']), 'ma phrase avec un hook final HOOK_FINAL');
				equal(__('my sentence with a final hook %s', 'HOOK_FINAL'), 'ma phrase avec un hook final HOOK_FINAL');
				equal(i18n.translate('%s my sentence with a start hook', ['HOOK_START']), 'HOOK_START ma phrase avec un hook en debut');
				equal(__('%s my sentence with a start hook', 'HOOK_START'), 'HOOK_START ma phrase avec un hook en debut');
				equal(i18n.translate('%s my sentence with a start and a final hooks %s', ['HOOK_START', 'HOOK_FINAL']),'HOOK_START ma phrase avec un hook en debut et en fin HOOK_FINAL');
				equal(__('%s my sentence with a start and a final hooks %s', 'HOOK_START', 'HOOK_FINAL'),'HOOK_START ma phrase avec un hook en debut et en fin HOOK_FINAL');
				equal(i18n.translate('%s', ['HOOK']), 'HOOK');
				equal(__('%s', 'HOOK'), 'HOOK');
				equal(i18n.translate('%s%s%s%s', ['HOOK1', 'HOOK2', 'HOOK3', 'HOOK4']), 'HOOK1HOOK2HOOK3HOOK4');
				equal(__('%s%s%s%s', 'HOOK1', 'HOOK2', 'HOOK3', 'HOOK4'), 'HOOK1HOOK2HOOK3HOOK4');
				start();
			}
		});
	});

	test('I18n : Not as many variables as they are hooks', function(){
		stop();
		mad.net.Ajax.singleton().request({
			'type':         'post',
			'url':          APP_URL+'/lg/jsDictionnary',
			'async':        false,
			'dataType':     'json',
			'success':      function(DATA){
				var i18n = mad.lang.I18n.singleton();
				i18n.loadDico(DATA);

				//no hook, variables given
				raises(function() {
					i18n.translate('my sentence without hook', ['HOOK']);
				}, mad.error.WrongParameters, mad.error.WrongParameters.message);

				raises(function() {
					__('my sentence without hook', 'HOOK');
				}, mad.error.WrongParameters, mad.error.WrongParameters.message);

				//hook, no variables given
				raises(function() {
					i18n.translate('my sentence with a final hook %s', []);
				}, mad.error.WrongParameters, mad.error.WrongParameters.message);

				raises(function() {
					__('my sentence with a final hook %s');
				}, mad.error.WrongParameters, mad.error.WrongParameters.message);

				//hook, not enough variables
				raises(function() {
					i18n.translate('%s my sentence with a final hook %s', ['HOOK_START']);
				}, mad.error.WrongParameters, mad.error.WrongParameters.message);

				raises(function() {
					__('%s my sentence with a final hook %s', 'HOOK_START');
				}, mad.error.WrongParameters, mad.error.WrongParameters.message);

				//hook, too much variables
				raises(function() {
					i18n.translate('%s my sentence with a final hook %s', ['HOOK_START', 'HOOK_END', 'BILOUTE']);
				}, mad.error.WrongParameters, mad.error.WrongParameters.message);

				raises(function() {
					__('%s my sentence with a final hook %s', 'HOOK_START', 'HOOK_END', 'BILOUTE');
				}, mad.error.WrongParameters, mad.error.WrongParameters.message);

				start();
			}
		});

	});

	test('I18n : Allowed scalar variables', function(){
		stop();
		mad.net.Ajax.singleton().request({
			'type':         'post',
			'url':          APP_URL+'/lg/jsDictionnary',
			'async':        false,
			'dataType':     'json',
			'success':      function(DATA){
				var i18n = mad.lang.I18n.singleton();
				i18n.loadDico(DATA);

				equal(i18n.translate('%s', [1]), '1');
				equal(__('%s', 1), '1');

				equal(i18n.translate('%s', [1.5]), '1.5');
				equal(__('%s', 1.5), '1.5');

				equal(i18n.translate('%s', [true]), 'true');
				equal(__('%s', true), 'true');

				start();
			}
		});

	});

	test('I18n : Wrong variables type', function(){
		stop();
		mad.net.Ajax.singleton().request({
			'type':         'post',
			'url':          APP_URL+'/lg/jsDictionnary',
			'async':        false,
			'dataType':     'json',
			'success':      function(DATA){
				var i18n = mad.lang.I18n.singleton();
				i18n.loadDico(DATA);

				//object not allowed
				raises(function() {
					i18n.translate('%s', [new Object()]);
				}, mad.error.WrongParameters, mad.error.WrongParameters.message);

				raises(function() {
					__('%s', new Object());
				}, mad.error.WrongParameters, mad.error.WrongParameters.message);

				//array not allowed
				raises(function() {
					i18n.translate('%s', [['A','B']]);
				}, mad.error.WrongParameters, mad.error.WrongParameters.message);

				raises(function() {
					__('%s', ['A','B']);
				}, mad.error.WrongParameters, mad.error.WrongParameters.message);

				start();
			}
		});

	});
	
});