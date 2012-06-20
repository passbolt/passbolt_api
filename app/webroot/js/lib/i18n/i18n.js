__ = function(str)
{
    var variables = [];
    for(var i in arguments){
        variables.push(arguments[i]);
    }
    translation = __.setVar(__.getEntry(str), variables.slice(1));
    return translation;
};

__.dico = [];

__.loadDico = function(lg)
{
    console.log('implement the load of the dictionnary in the given language');
}

__.setVar = function(str, variables)
{
    var returnValue = '';
    var split = str.split('%s');
    console.log(split);
    if(split.length != variables.length+1){
        throw new Error('Not the same number of args');
    }
    for(var i in variables){
        returnValue += split[i]+variables[i];
    }
    return returnValue;
}

__.getEntry = function(str)
{
    var returnValue = str;
    if(typeof __.dico[str] != 'undefined'){
        returnValue =  __.dico[str];
    }
    return returnValue;
}

