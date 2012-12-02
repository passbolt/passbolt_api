steal('funcunit', function () {

	module("mad.model", {
		// runs before each test
		setup: function () {},
		// runs after each test
		teardown: function () {}
	});

	var htmlTags = ['<!DOCTYPE>', '<a>', '<abbr>', '<acronym>', '<address>', '<applet>', '<area>', '<article>', '<aside>', '<audio>', '<b>', '<base>', '<basefont>', '<bdi>', '<bdo>', '<big>', '<blockquote>', '<body>', '<br>', '<button>', '<canvas>', '<caption>', '<center>', '<cite>', '<code>', '<col>', '<colgroup>', '<command>', '<datalist>', '<dd>', '<del>', '<details>', '<dfn>', '<dir>', '<div>', '<dl>', '<dt>', '<em>', '<embed>', '<fieldset>', '<figcaption>', '<figure>', '<font>', '<footer>', '<form>', '<frame>', '<frameset>', '<head>', '<header>', '<hgroup>', '<h1> - <h6>', '<hr>', '<html>', '<i>', '<iframe>', '<img>', '<input>', '<ins>', '<kbd>', '<keygen>', '<label>', '<legend>', '<li>', '<link>', '<map>', '<mark>', '<menu>', '<meta>', '<meter>', '<nav>', '<noframes>', '<noscript>', '<object>', '<ol>', '<optgroup>', '<option>', '<output>', '<p>', '<param>', '<pre>', '<progress>', '<q>', '<rp>', '<rt>', '<ruby>', '<s>', '<samp>', '<script>', '<section>', '<select>', '<small>', '<source>', '<span>', '<strike>', '<strong>', '<style>', '<sub>', '<summary>', '<sup>', '<table>', '<tbody>', '<td>', '<textarea>', '<tfoot>', '<th>', '<thead>', '<time>', '<title>', '<tr>', '<track>', '<tt>', '<u>', '<ul>', '<var>', '<video>', '<wbr>'];
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
		'date': '01/01/2012',
		'html': '<h1>La solution gestion de mot de passe</h1> parfaite pour les <b>business</b> et les <span style="font-size:10px">petites</span> entreprises sans oublier les accents <span style="background: url()">indispensables</span> dans l\'alphabet latin ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÚÚÚÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ'
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
		str = '';
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
		////////////////////////////////////////////////////////////////////////
		// TRUE Excepted
		////////////////////////////////////////////////////////////////////////
		ok(mad.model.ValidationRules.validate('email', samples['email']) === true, samples['email']);
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
		var str = '',
			dates = {
				'mm/dd/yy' : {
					'valid' : ['01/20/12', '1/1/12'],
					'invalid' : ['20/1/12', '1/32/12', '1/20/2012']
				},
				'mm/dd/yyyy' : {
					'valid' : ['01/20/2012', '1/20/2012'],
					'invalid' : ['20/1/12', '1/32/12']
				},
				'dd/mm/yyyy': {
					'valid' :  ['20/01/2012', '20/1/2012'],
					'invalid' : ['41/1/12', '1/32/12']
				},
				'd/m/yy': {
					'valid' :  ['20/1/12', '20/1/2012'],
					'invalid' : ['41/1/12', '1/32/12']
				},
				'y/m/d' : {
					'valid' :  ['12/1/20', '2012/1/20'],
					'invalid' : ['12/32/1', '12/1/32']
				},
				'yy/mm/dd' : {
					'valid' :  ['12/1/20', '2012/1/20'],
					'invalid' : ['12/32/1', '12/1/32']
				},
				'yyyy/mm/dd' : {
					'valid' :  ['2012/01/20'],
					'invalid' : ['12/1/1', '2012/13/1', '2012/1/32']
				}
			}
		
		////////////////////////////////////////////////////////////////////////
		// TRUE Excepted
		////////////////////////////////////////////////////////////////////////
		equal(mad.model.ValidationRules.validate('date', samples['date']), true, 'default format with ' + samples['date']);
		for (var format in dates) {
			for (var i in dates[format]['valid']){
				equal(mad.model.ValidationRules.validate('date', dates[format]['valid'][i], {'format':format}), true, format + ' format with ' + dates[format]['valid'][i]);
			}
		}
		////////////////////////////////////////////////////////////////////////
		// FALSE Excepted
		////////////////////////////////////////////////////////////////////////
		for (var format in dates) {
			for (var i in dates[format]['invalid']){
				notEqual(mad.model.ValidationRules.validate('date', dates[format]['invalid'][i], {'format':format}), true, format + ' format with ' + dates[format]['invalid'][i]);
			}
		}
		notEqual(mad.model.ValidationRules.validate('date', samples['alphaASCII']), true, samples['alphaASCII']);
		notEqual(mad.model.ValidationRules.validate('date', samples['alphaASCIIUpper']), true, samples['alphaASCIIUpper']);
		notEqual(mad.model.ValidationRules.validate('date', samples['alphaAccent']), true, samples['alphaAccent']);
		notEqual(mad.model.ValidationRules.validate('date', samples['alphaLatin']), true, samples['alphaLatin']);
		notEqual(mad.model.ValidationRules.validate('date', samples['alphaChinese']), true, samples['alphaChinese']);
		notEqual(mad.model.ValidationRules.validate('date', samples['alphaArabic']), true, samples['alphaArabic']);
		notEqual(mad.model.ValidationRules.validate('date', samples['alphaRussian']), true, samples['alphaRussian']);
		notEqual(mad.model.ValidationRules.validate('date', samples['digit']), true, samples['digit']);
		notEqual(mad.model.ValidationRules.validate('date', samples['float']), true, samples['float']);
		notEqual(mad.model.ValidationRules.validate('date', samples['special']), true, samples['special']);
	});
	
	test('mad.model.ValidationRules : size', function () {
		var str = '';
		////////////////////////////////////////////////////////////////////////
		// TRUE Excepted
		////////////////////////////////////////////////////////////////////////
		str = "abcd"
		equal(mad.model.ValidationRules.validate('size', str, {'min':3}), true, 'min 3 with ' + str);
		str = "ab"
		equal(mad.model.ValidationRules.validate('size', str, {'max':3}), true, 'max 3 with ' + str);
		str = "abcde"
		equal(mad.model.ValidationRules.validate('size', str, {'min':3, 'max':8}), true, 'min 3 max 8 with ' + str);
		////////////////////////////////////////////////////////////////////////
		// FALSE Excepted
		////////////////////////////////////////////////////////////////////////
		str = "ab"
		notEqual(mad.model.ValidationRules.validate('size', str, {'min':3}), true, 'min 3 with' + str);
		str = "abcd"
		notEqual(mad.model.ValidationRules.validate('size', str, {'max':3}), true, 'max 3 with ' + str);
		str = "ab"
		notEqual(mad.model.ValidationRules.validate('size', str, {'min':3, 'max':8}), true, 'min 3 max 8 with ' + str);
		str = "abcdefghi"
		notEqual(mad.model.ValidationRules.validate('size', str, {'min':3, 'max':8}), true, 'min 3 max 8 with ' + str);
	});
	
	test('mad.model.ValidationRules : nospace', function () {
		var str = '';
		////////////////////////////////////////////////////////////////////////
		// TRUE Excepted
		////////////////////////////////////////////////////////////////////////
		equal(mad.model.ValidationRules.validate('nospace', samples['alphaASCII']), true, samples['alphaASCII']);
		equal(mad.model.ValidationRules.validate('nospace', samples['alphaASCIIUpper']), true, samples['alphaASCIIUpper']);
		equal(mad.model.ValidationRules.validate('nospace', samples['alphaAccent']), true, samples['alphaAccent']);
		equal(mad.model.ValidationRules.validate('nospace', samples['digit']), true, samples['digit']);
		////////////////////////////////////////////////////////////////////////
		// FALSE Excepted
		////////////////////////////////////////////////////////////////////////		
		str = ' ' + samples['alphaASCII'];
		notEqual(mad.model.ValidationRules.validate('nospace', str), true, str);
		str = samples['alphaASCII'] + ' ';
		notEqual(mad.model.ValidationRules.validate('nospace', str), true, str);
		str = samples['alphaASCII'] + ' ' + samples['alphaASCII'];
		notEqual(mad.model.ValidationRules.validate('nospace', str), true, str);
	});
	
	test('mad.model.ValidationRules : text', function () {
		var str = '';
		////////////////////////////////////////////////////////////////////////
		// TRUE Excepted
		////////////////////////////////////////////////////////////////////////
		equal(mad.model.ValidationRules.validate('required', samples['email']),  true, samples['email']);
		equal(mad.model.ValidationRules.validate('required', samples['alphaASCII']), true, samples['alphaASCII']);
		equal(mad.model.ValidationRules.validate('required', samples['alphaASCIIUpper']), true, samples['alphaASCIIUpper']);
		equal(mad.model.ValidationRules.validate('required', samples['alphaAccent']), true, samples['alphaAccent']);
		equal(mad.model.ValidationRules.validate('required', samples['alphaLatin']), true, samples['alphaLatin']);
		equal(mad.model.ValidationRules.validate('required', samples['alphaChinese']), true, samples['alphaChinese']);
		equal(mad.model.ValidationRules.validate('required', samples['alphaArabic']), true, samples['alphaArabic']);
		equal(mad.model.ValidationRules.validate('required', samples['alphaRussian']), true, samples['alphaRussian']);
		equal(mad.model.ValidationRules.validate('required', samples['digit']), true, samples['digit']);
		equal(mad.model.ValidationRules.validate('required', samples['float']), true, samples['float']);
		equal(mad.model.ValidationRules.validate('required', samples['special']), true, samples['special']);
		////////////////////////////////////////////////////////////////////////
		// FALSE Excepted
		////////////////////////////////////////////////////////////////////////
		notEqual(mad.model.ValidationRules.validate('text', samples['html']), true, samples['html']);
	});
});