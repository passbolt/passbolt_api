exports.instantiate = function(load) {
  load.metadata.deps = [];
  load.metadata.execute = function(){
	return System.newModule({ pluginSource: load.source });
  };
  load.metadata.format = "css";
};