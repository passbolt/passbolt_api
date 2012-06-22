steal( 
    '//lib/mad/core/singleton.js'
)
.then( 
    function($){
        
        mad.core.Singleton.extend('mad.core.SingletonTest',{},{});
        var s1 = new mad.core.Singleton();
        var s2 = new mad.core.SingletonTest();
        var s1Prime = mad.core.Singleton.singleton();
        var s2Prime = mad.core.SingletonTest.singleton();
    }
);
