//require
steal('mad/core/singleton.js');

module("MadSquirrel", {
    
    // runs before each test
    setup: function(){
    },
    // runs after each test
    teardown: function(){
    }
});

test('Check that a singleton is instanciated once', function(){
   mad.core.Singleton.extend('made.test.SingletonUnitTest1', {
       'constStaticVar1':'constStaticVar1'
   }, {
       'instanceVar1':null,
       'init':function(){
           this.instanceVar1 = 'instanceVar1';
       }
   });
   mad.core.Singleton.extend('made.test.SingletonUnitTest2', {
       'constStaticVar2':'constStaticVar2'
   }, {
       'instanceVar2':null,
       'init':function(){
           this.instanceVar2 = 'instanceVar2';
       }
   });
   var s1 = new made.test.SingletonUnitTest1();
   var s2 = new made.test.SingletonUnitTest2();
   
   equal(made.test.SingletonUnitTest1.constStaticVar1, 'constStaticVar1', 'Expected value for static variables of singleton 1 ');
   equal(made.test.SingletonUnitTest2.constStaticVar2, 'constStaticVar2', 'Expected value for static variables of singleton 2 ');
   equal(s1.instanceVar1, 'instanceVar1', 'Expected value for instance variables of singleton 1 ');
   equal(s2.instanceVar2, 'instanceVar2', 'Expected value for instance variables of singleton 2 ');
});
