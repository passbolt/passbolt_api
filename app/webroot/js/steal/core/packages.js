//  ============================== Packages ===============================
/**
 * @function packages
 * `steal.packages( packageIds... )` defines modules for deferred downloading.
 * 
 * This is used by the build system to build collections of modules that will be downloaded
 * after initial page load.
 * 
 * For example, an application that wants to progressively load the contents and
 * dependencies of _login/login.js_, _filemanager/filemanager.js_, and _contacts/contacts.js_,
 * while immediately loading the current users's data might look like:
 * 
 *     steal.packages('login','filemanager','contacts')
 *     steal('models/user', function(User){
 * 	   
 *       // get the current User
 *       User.findOne({id: "current"}, 
 * 
 *         // success - they logged in
 *         function(user){
 *           if(window.location.hash == "#filemanager"){
 *             steal('filemanager')  
 *           }
 *         }, 
 *         // error - they are logged out
 *         function(){
 *           steal('login', function(){
 *             new Login(document.body);
 *             // preload filemanager
 * 
 *           })  
 *         })
 *     })
 * 
 *
 * 		steal.packages('tasks','dashboard','fileman');
 *
 */
st.packs = [];
st.packHash = {};
st.packages = function( map ) {

	if (!arguments.length ) {
		return st.packs;
	} else {
		if ( typeof map == 'string' ) {
			st.packs.push.apply(st.packs, arguments);
		} else {
			st.packHash = map;
		}

		return this;
	}
};
