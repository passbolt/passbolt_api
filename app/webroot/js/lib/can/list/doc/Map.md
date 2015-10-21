@page can.List.Map Map
@parent can.List.static

@property {can.Map} can.List.Map

@description Specify the Map type used to make objects added to this list observable.

@option {can.Map} When objects are added to a can.List, those objects are converted into can.Map instances. For example:

     var list = new can.List();
     list.push({name: "Justin"});

     var map = list.attr(0);
     map.attr("name") //-> "Justin"

By changing [can.List.Map], you can specify a different type of Map instance to create. For example:

     var User = can.Map.extend({
       fullName: function(){
         return this.attr("first")+" "+this.attr("last")
       }
     });

     User.List = can.List.extend({
       Map: User
     }, {});

     var list = new User.List();
     list.push({first: "Justin", last: "Meyer"});

     var user = list.attr(0);
     user.fullName() //-> "Justin Meyer"