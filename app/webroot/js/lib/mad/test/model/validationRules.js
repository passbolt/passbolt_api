steal('funcunit', function () {

	module("mad.controller", {
		// runs before each test
		setup: function () {},
		// runs after each test
		teardown: function () {}
	});

	var samples = {
		'alphaASCII': 'abcdefghijklmnopqrstuvwxyz',
		'alphaASCIIUpper' : 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
		'alphaAccent': 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÚÚÚÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ',
		'alphaLatin': 'La solution gestion de mot de passe parfaite pour les business et les petites entreprises sans oublier les accents indispensables dans l\'alphabet latin ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÚÚÚÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ',
		'alphaChinese': '完善的密碼管理解決方案 為小型公司和企業的商人',	// The perfect password management solution for businesses and small companies
		'alphaArabic': 'إدارة كلمة المرور الحل المثالي للشركات الصغيرة والشركات رجل الأعمال', // The perfect password management solution for businesses and small companies
		'alphaRussian': 'Идеальное решение для управления пароль для небольших компаний и предприятий бизнесмена', // The perfect password management solution for businesses and small companies
		'digit': '0123456789',
		'float': '3.57',
		'special': '!@#$%^&*()_-+={}[]:";<>?,./\\|~',
		'null': null,
		'email': 'passbolt_team-2012@passbolt_team-2012.com',
		'date': '01/01/2012'
	};

	test('mad.model.ValidationRules : alpha ASCII', function () {
		////////////////////////////////////////////////////////////////////////
		// TRUE Excepted
		////////////////////////////////////////////////////////////////////////
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaASCII'], {'type':'ASCII'}) === true, samples['alphaASCII']);
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaASCIIUpper'], {'type':'ASCII'}) === true, samples['alphaASCIIUpper']);
		////////////////////////////////////////////////////////////////////////
		// FALSE Excepted
		////////////////////////////////////////////////////////////////////////
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaAccent'], {'type':'ASCII'}) !== true, samples['alphaAccent']);
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaLatin'], {'type':'ASCII'}) !== true, samples['alphaLatin']);
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaChinese'], {'type':'ASCII'}) !== true, samples['alphaChinese']);
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaArabic'], {'type':'ASCII'}) !== true, samples['alphaArabic']);
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaRussian'], {'type':'ASCII'}) !== true, samples['alphaRussian']);
		ok(mad.model.ValidationRules.validate('alpha', samples['special'], {'type':'ASCII'}) !== true, samples['special']);
		ok(mad.model.ValidationRules.validate('alpha', samples['digit'], {'type':'ASCII'}) !== true, samples['digit']);
		ok(mad.model.ValidationRules.validate('alpha', samples['float'], {'type':'ASCII'}) !== true, samples['float']);
		ok(mad.model.ValidationRules.validate('alpha', samples['email'], {'type':'ASCII'}) !== true, samples['email']);
	});

	test('mad.model.ValidationRules : alpha', function () {
		////////////////////////////////////////////////////////////////////////
		// TRUE Excepted
		////////////////////////////////////////////////////////////////////////
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaASCII']) === true, samples['alphaASCII']);
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaASCIIUpper']) === true, samples['alphaASCIIUpper']);
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaAccent']) === true, samples['alphaAccent']);
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaLatin']) === true, samples['alphaLatin']);
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaChinese']) === true, samples['alphaChinese']);
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaArabic']) === true, samples['alphaArabic']);
		ok(mad.model.ValidationRules.validate('alpha', samples['alphaRussian']) === true, samples['alphaRussian']);
		////////////////////////////////////////////////////////////////////////
		// FALSE Excepted
		////////////////////////////////////////////////////////////////////////
		ok(mad.model.ValidationRules.validate('alpha', samples['special']) !== true, samples['special']);
		ok(mad.model.ValidationRules.validate('alpha', samples['digit']) !== true, samples['digit']);
		ok(mad.model.ValidationRules.validate('alpha', samples['float']) !== true, samples['float']);
		ok(mad.model.ValidationRules.validate('alpha', samples['email']) !== true, samples['email']);
	});

	test('mad.model.ValidationRules : alphanum', function () {
		////////////////////////////////////////////////////////////////////////
		// TRUE Excepted
		////////////////////////////////////////////////////////////////////////
		ok(mad.model.ValidationRules.validate('alphanum', samples['alphaASCII']) === true, samples['alphaASCII']);
		ok(mad.model.ValidationRules.validate('alphanum', samples['alphaASCIIUpper']) === true, samples['alphaASCIIUpper']);
		ok(mad.model.ValidationRules.validate('alphanum', samples['alphaAccent']) === true, samples['alphaAccent']);
		ok(mad.model.ValidationRules.validate('alphanum', samples['alphaLatin']) === true, samples['alphaLatin']);
		ok(mad.model.ValidationRules.validate('alphanum', samples['alphaChinese']) === true, samples['alphaChinese']);
		ok(mad.model.ValidationRules.validate('alphanum', samples['alphaArabic']) === true, samples['alphaArabic']);
		ok(mad.model.ValidationRules.validate('alphanum', samples['alphaRussian']) === true, samples['alphaRussian']);
		ok(mad.model.ValidationRules.validate('alphanum', samples['digit']) === true, samples['digit']);
		
		////////////////////////////////////////////////////////////////////////
		// FALSE Excepted
		////////////////////////////////////////////////////////////////////////
		ok(mad.model.ValidationRules.validate('alphanum', samples['email']) !== true, samples['email']);
		ok(mad.model.ValidationRules.validate('alphanum', samples['float']) !== true, samples['float']);
		ok(mad.model.ValidationRules.validate('alphanum', samples['special']) !== true, samples['special']);
	});

	test('mad.model.ValidationRules : num', function () {
		var str = '';
		
		////////////////////////////////////////////////////////////////////////
		// TRUE Excepted
		////////////////////////////////////////////////////////////////////////
		// integer
		ok(mad.model.ValidationRules.validate('num', samples['digit']) === true, samples['digit']);
		// negative integer
		ok(mad.model.ValidationRules.validate('num', '-' + samples['digit']) === true, '-' + samples['digit']);
		// float
		ok(mad.model.ValidationRules.validate('num', samples['float']) === true, samples['float']);
		// negative float
		ok(mad.model.ValidationRules.validate('num', '-' + samples['float']) === true, '-' + samples['float']);
		// float with 0 in first char
		str = '0.233';
		ok(mad.model.ValidationRules.validate('num', str) === true, str);
	
		////////////////////////////////////////////////////////////////////////
		// FALSE Excepted
		////////////////////////////////////////////////////////////////////////
		// mal formed float
		str = '3..57';
		ok(mad.model.ValidationRules.validate('num', str) !== true, str);
		str = '.57';
		ok(mad.model.ValidationRules.validate('num', str) !== true, str);
		
		// samples
		ok(mad.model.ValidationRules.validate('num', samples['email']) !== true, samples['email']);
		ok(mad.model.ValidationRules.validate('num', samples['alphaASCII']) !== true, samples['alphaASCII']);
		ok(mad.model.ValidationRules.validate('num', samples['alphaASCIIUpper']) !== true, samples['alphaASCIIUpper']);
		ok(mad.model.ValidationRules.validate('num', samples['alphaAccent']) !== true, samples['alphaAccent']);
		ok(mad.model.ValidationRules.validate('num', samples['alphaLatin']) !== true, samples['alphaLatin']);
		ok(mad.model.ValidationRules.validate('num', samples['alphaChinese']) !== true, samples['alphaChinese']);
		ok(mad.model.ValidationRules.validate('num', samples['alphaArabic']) !== true, samples['alphaArabic']);
		ok(mad.model.ValidationRules.validate('num', samples['alphaRussian']) !== true, samples['alphaRussian']);
		ok(mad.model.ValidationRules.validate('num', samples['special']) !== true, samples['special']);
	});

	test('mad.model.ValidationRules : required', function () {
		var str = '';
		
		////////////////////////////////////////////////////////////////////////
		// TRUE Excepted
		////////////////////////////////////////////////////////////////////////
		ok(mad.model.ValidationRules.validate('required', samples['email']) === true, samples['email']);
		ok(mad.model.ValidationRules.validate('required', samples['alphaASCII']) === true, samples['alphaASCII']);
		ok(mad.model.ValidationRules.validate('required', samples['alphaASCIIUpper']) === true, samples['alphaASCIIUpper']);
		ok(mad.model.ValidationRules.validate('required', samples['alphaAccent']) === true, samples['alphaAccent']);
		ok(mad.model.ValidationRules.validate('required', samples['alphaLatin']) === true, samples['alphaLatin']);
		ok(mad.model.ValidationRules.validate('required', samples['alphaChinese']) === true, samples['alphaChinese']);
		ok(mad.model.ValidationRules.validate('required', samples['alphaArabic']) === true, samples['alphaArabic']);
		ok(mad.model.ValidationRules.validate('required', samples['alphaRussian']) === true, samples['alphaRussian']);
		ok(mad.model.ValidationRules.validate('required', samples['digit']) === true, samples['digit']);
		ok(mad.model.ValidationRules.validate('required', samples['float']) === true, samples['float']);
		ok(mad.model.ValidationRules.validate('required', samples['special']) === true, samples['special']);
	
		////////////////////////////////////////////////////////////////////////
		// FALSE Excepted
		////////////////////////////////////////////////////////////////////////
		ok(mad.model.ValidationRules.validate('required', samples['null']) !== true, samples['null']);
		var str = '';
		ok(mad.model.ValidationRules.validate('required', str) !== true, 'empty string');
		str = ' ';
		ok(mad.model.ValidationRules.validate('required', str) !== true, 'string containing only one space');
		str = '      ';
		ok(mad.model.ValidationRules.validate('required', str) !== true, 'string containing multiple spaces');
		str = '\n';
		ok(mad.model.ValidationRules.validate('required', str) !== true, 'string containing only eof');
		str = '\t';
		ok(mad.model.ValidationRules.validate('required', str) !== true, 'string containing only tab');
	});

	test('mad.model.ValidationRules : email', function () {
		var str = '';
		
		////////////////////////////////////////////////////////////////////////
		// TRUE Excepted
		////////////////////////////////////////////////////////////////////////
		ok(mad.model.ValidationRules.validate('email', samples['email']) === true, samples['email']);
		var str = '';
		
		////////////////////////////////////////////////////////////////////////
		// FALSE Excepted
		////////////////////////////////////////////////////////////////////////
		ok(mad.model.ValidationRules.validate('email', samples['alphaASCII']) !== true, samples['alphaASCII']);
		ok(mad.model.ValidationRules.validate('email', samples['alphaASCIIUpper']) !== true, samples['alphaASCIIUpper']);
		ok(mad.model.ValidationRules.validate('email', samples['alphaAccent']) !== true, samples['alphaAccent']);
		ok(mad.model.ValidationRules.validate('email', samples['alphaLatin']) !== true, samples['alphaLatin']);
		ok(mad.model.ValidationRules.validate('email', samples['alphaChinese']) !== true, samples['alphaChinese']);
		ok(mad.model.ValidationRules.validate('email', samples['alphaArabic']) !== true, samples['alphaArabic']);
		ok(mad.model.ValidationRules.validate('email', samples['alphaRussian']) !== true, samples['alphaRussian']);
		ok(mad.model.ValidationRules.validate('email', samples['digit']) !== true, samples['digit']);
		ok(mad.model.ValidationRules.validate('email', samples['float']) !== true, samples['float']);
		ok(mad.model.ValidationRules.validate('email', samples['special']) !== true, samples['special']);
	});

	test('mad.model.ValidationRules : date', function () {
		var str = '';
		
		////////////////////////////////////////////////////////////////////////
		// TRUE Excepted
		////////////////////////////////////////////////////////////////////////
		ok(mad.model.ValidationRules.validate('date', samples['date']) === true, samples['date']);
		ok(mad.model.ValidationRules.validate('date', samples['date']) === true, samples['date']);
		
		////////////////////////////////////////////////////////////////////////
		// FALSE Excepted
		////////////////////////////////////////////////////////////////////////
		ok(mad.model.ValidationRules.validate('date', samples['alphaASCII']) !== true, samples['alphaASCII']);
		ok(mad.model.ValidationRules.validate('date', samples['alphaASCIIUpper']) !== true, samples['alphaASCIIUpper']);
		ok(mad.model.ValidationRules.validate('date', samples['alphaAccent']) !== true, samples['alphaAccent']);
		ok(mad.model.ValidationRules.validate('date', samples['alphaLatin']) !== true, samples['alphaLatin']);
		ok(mad.model.ValidationRules.validate('date', samples['alphaChinese']) !== true, samples['alphaChinese']);
		ok(mad.model.ValidationRules.validate('date', samples['alphaArabic']) !== true, samples['alphaArabic']);
		ok(mad.model.ValidationRules.validate('date', samples['alphaRussian']) !== true, samples['alphaRussian']);
		ok(mad.model.ValidationRules.validate('date', samples['digit']) !== true, samples['digit']);
		ok(mad.model.ValidationRules.validate('date', samples['float']) !== true, samples['float']);
		ok(mad.model.ValidationRules.validate('date', samples['special']) !== true, samples['special']);
	});
	

	test('mad.model.ValidationRules : size', function () {
		var str = '';
		
		////////////////////////////////////////////////////////////////////////
		// TRUE Excepted
		////////////////////////////////////////////////////////////////////////
		str = "abcd"
		ok(mad.model.ValidationRules.validate('size', str, {'min':3}) === true, 'min 3 with ' + str);
		str = "ab"
		ok(mad.model.ValidationRules.validate('size', str, {'max':3}) === true, 'max 3 with ' + str);
		str = "abcde"
		ok(mad.model.ValidationRules.validate('size', str, {'min':3, 'max':8}) === true, 'min 3 max 8 with ' + str);
		
		////////////////////////////////////////////////////////////////////////
		// FALSE Excepted
		////////////////////////////////////////////////////////////////////////
		str = "ab"
		ok(mad.model.ValidationRules.validate('size', str, {'min':3}) !== true, 'min 3 with' + str);
		str = "abcd"
		ok(mad.model.ValidationRules.validate('size', str, {'max':3}) !== true, 'max 3 with ' + str);
		str = "ab"
		ok(mad.model.ValidationRules.validate('size', str, {'min':3, 'max':8}) !== true, 'min 3 max 8 with ' + str);
		str = "abcdefghi"
		ok(mad.model.ValidationRules.validate('size', str, {'min':3, 'max':8}) !== true, 'min 3 max 8 with ' + str);
	});
});