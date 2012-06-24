steal( 
    '//lib/mad/core/singleton.js'
)
.then( 
    function($){
        
        mad.core.Singleton.extend('mad.core.SingletonTest',{},{});
        mad.core.Singleton.extend('mad.core.SingletonTest2',{},{});
//        var s1 = mad.core.Singleton.singleton();
        var s2 = new mad.core.SingletonTest();
        var s3 = new mad.core.SingletonTest2();
//        var s1Prime = mad.core.Singleton.singleton();
        var s2Prime = mad.core.SingletonTest.singleton();
        var s3Prime = mad.core.SingletonTest2.singleton();
    }
);
