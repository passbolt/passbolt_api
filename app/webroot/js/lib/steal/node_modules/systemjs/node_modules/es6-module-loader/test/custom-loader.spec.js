//

describe('Custom Loader', function () {

  describe('#import', function () {

    describe('scripts', function () {
      it('should support ES6 scripts', function (done) {
        customLoader.import('test/loader/test')
          .then(function (m) {
            expect(m.loader).to.be.equal('custom');
          })
          .then(done, done)
      });

      it('should support AMD scripts', function (done) {
        customLoader.import('test/loader/amd')
          .then(function (m) {
            expect(m.format).to.be.equal('amd');
          })
          .then(done, done);
      });
    });

    describe('special #locate path rule', function a() {

      it('should support special loading rules', function (done) {
        customLoader.import('path/custom')
          .then(function (m) {
            expect(m.path).to.be.ok();
          })
          .then(done, done);
      })

    });

    describe('errors', function () {

      function supposeToFail() {
        expect(false, 'should not be successful').to.be.ok();
      }

      it('should make the normalize throw', function (done) {
        customLoader.import('test/loader/error1-parent')
          .then(supposeToFail, function (e) {
            expect(e).to.be.match(/Error loading "test\/loader\/error1-parent" at \S+error1-parent\.js/);
          })
          .then(done, done);
      });

      it('should make the locate throw', function (done) {
        customLoader.import('test/loader/error2')
          .then(supposeToFail, function (e) {
            expect(e).to.be.match(/Error loading "test\/loader\/error2" at \S+test\/loader\/error2\.js/);
          })
          .then(done, done);
      });

      it('should make the fetch throw', function (done) {
        customLoader.import('test/loader/error3')
          .then(supposeToFail, function (e) {
            expect(e).to.be.match(/Error loading "test\/loader\/error3" at \S+test\/loader\/error3\.js/);
          })
          .then(done, done);
      });

      it('should make the translate throw', function (done) {
        customLoader.import('test/loader/error4')
          .then(supposeToFail, function (e) {
            expect(e).to.be.match(/Error loading "test\/loader\/error4" at \S+test\/loader\/error4\.js/);
          })
          .then(done, done);
      });

      it('should make the instantiate throw', function (done) {
        customLoader.import('test/loader/error5')
          .then(supposeToFail, function (e) {
            expect(e).to.be.match(/Error loading "test\/loader\/error5" at \S+test\/loader\/error5\.js/);
          })
          .then(done, done);
      });

    });

  });

  describe('#normalize', function () {
    it('should support async normalization', function (done) {
      customLoader.normalize('asdfasdf')
        .then(function (normalized) {
          return customLoader.import(normalized);
        })
        .then(function (m) {
          expect(m.n).to.be.equal('n');
        })
        .then(done, done);
    });
  });
});
