module("MadSquirrel", {
    // runs before each test
    setup: function(){
    },
    // runs after each test
    teardown: function(){
    }
});

//
$.fixture({
    type: 'post',  
    url: '/ajax/request1'
},
function(settings){
    return 'request1';
});

//
$.fixture({
    type: 'post',  
    url: APP_URL+'/ajax/requests'
},
function(settings){
    return 'root url';
});

//test('Ajax : Simple request', function(){
//    stop();
//    mad.net.Ajax.singleton().request({
//        'type':         'post',
//        'url':          '/ajax/request1',
//        'async':        false,
//        'dataType':     'json',
//        'success':      function(DATA){
//            equal(DATA, 'request1');
//            start();
//        }
//    });
//});

test('Ajax : Simple request', function(){
    stop();
    mad.net.Ajax.singleton().request({
        'type':         'post',
        'url':          APP_URL+'/ajax/request1',
        'async':        true,
        'dataType':     'json',
        'success':      function(DATA){
            equal(DATA, 'root_url');
            start();
        }
    }, true);
});