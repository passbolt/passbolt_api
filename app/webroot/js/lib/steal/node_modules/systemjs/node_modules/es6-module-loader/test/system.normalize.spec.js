//

describe('System', function () {
  describe('#normalize', function () {

    // Normalize tests - identical to https://github.com/google/traceur-compiler/blob/master/test/unit/runtime/System.js

    var originBaseUrl = System.baseURL;

    beforeEach(function () {
      System.baseURL = 'http://example.org/a/b.html';
    });

    afterEach(function () {
      System.baseURL = originBaseUrl;
    });

    describe('when having no argument', function () {

      it('should throw with no specified name', function () {
        expect(function () { System.normalize(); })
          .to.throwException(function (e) {
            expect(e).to.be.a(TypeError);
            expect(e.message).to.match(/Module name must be a string/);
          });
      });

    });

    describe('when having one argument', function () {

      it('should not referer', function () {
        expect(System.normalize('d/e/f')).to.equal('d/e/f');
      });

      it.skip('should "below baseURL"', function () {
        expect(System.normalize('../e/f')).to.equal('../e/f');
      });

      it('should be backwards compat', function () {
        expect(System.normalize('./a.js')).to.equal('a.js');
      });

      it('should throw with an url as name', function () {
        expect(function () { System.normalize('http://example.org/a/b.html'); })
          .to.throwException(function (e) {
            expect(e).to.be.a(TypeError);
            expect(e.message).to.match(/Illegal module name "\S+"/);
          });
      });

      it('should throw with embedded path', function () {
        expect(function () { System.normalize('a/b/../c'); })
          .to.throwException(function (e) {
            expect(e).to.be.a(TypeError);
            expect(e.message).to.match(/Illegal module name "\S+"/);
          });
      });

    });

    describe('when having two arguments', function () {

      var refererName = 'dir/file';

      it('should support relative path', function () {
        expect(System.normalize('./d/e/f', refererName)).to.equal('dir/d/e/f');
        expect(System.normalize('../e/f', refererName)).to.equal('e/f');
      });

      it('should resolve the path with relative parent', function () {
        expect(System.normalize('./a/b', 'c')).to.equal('a/b');
        expect(System.normalize('./a/b', 'c/d')).to.equal('c/a/b');
        expect(System.normalize('./a/b', '../c/d')).to.equal('../c/a/b');
        expect(System.normalize('./a/b', '../../c/d')).to.equal('../../c/a/b');
      });

      it('should throw with embedded path', function () {

        expect(function () { System.normalize('a/b/../c'); })
          .to.throwException(function (e) {
            expect(e).to.be.a(TypeError);
            expect(e.message).to.match(/Illegal module name "\S+"/);
          });

        expect(function () { System.normalize('a/../b'); })
          .to.throwException(function (e) {
            expect(e).to.be.a(TypeError);
            expect(e.message).to.match(/Illegal module name "\S+"/);
          });

      });
    });
  });

  describe('#locate', function () {

    beforeEach(function () {
      System.baseURL = 'http://example.org/a/';
    });

    it('should resolve paths', function () {
      expect(System.locate({name: '@abc/def'}))
        .to.equal('http://example.org/a/@abc/def.js');
      expect(System.locate({name: ' abc/def'}))
        .to.equal('http://example.org/a/abc/def.js');
    });

    it('should resolve paths with the existing config', function () {
      System.paths['path/*'] = '/test/*.js';
      expect(System.locate({name: 'path/test'}))
        .to.equal('http://example.org/test/test.js');
    });

  });
});
