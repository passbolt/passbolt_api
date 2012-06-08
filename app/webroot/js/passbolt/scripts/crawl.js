// load('passbolt/scripts/crawl.js')

load('steal/rhino/rhino.js')

steal('steal/html/crawl', function(){
  steal.html.crawl("passbolt/passbolt.html","passbolt/out")
});
