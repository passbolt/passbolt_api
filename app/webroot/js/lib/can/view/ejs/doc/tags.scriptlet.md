@function can.ejs.tags.scriptlet <% CODE %>
@parent can.ejs.tags 0

@signature `<% CODE %>`

Runs JavaScript Code.

This type of magic tag does not modify the template but is used for JS control statements 
like for-loops, if/else, switch, etc.  An example:

    <% if( items.attr('length') === 0 ) { %>
        <tr><td>You have no items</td></tr>
    <% } else { %>
        <% items.each(function(item){ %>
          <tr> .... </tr>
        <% }) %>
    <% } %>

Variable declarations and control blocks should always be defined in 
their own dedicated tags. Live binding leverages this hinting to ensure that logic is declared and executed at its intended scope.
	
	<!-- Each statement has its own dedicated EJS tag -->
    <% var address = person.attr('address') %>
    <% items.each(function(item){ %>
        <tr> .... </tr>
    <% }) %>
    <span><%= address.attr('street') %><span>
    
    <!-- This won't work! -->
    <%
      var address = person.attr('address');
      items.each(function(item) {
    %>
        <tr> .... </tr>
    <% }) %>
    <span><%= address.attr('street') %><span>