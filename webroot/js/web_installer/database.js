$(function() {
  $("#driver")
    .chosen({width: '100%', disable_search: true})
    .change(function () {
      let driver = $("#driver").val();
      if (driver === 'Cake\\Database\\Driver\\Postgres') {
        $("#port").val('5432');
      } else if (driver === 'Cake\\Database\\Driver\\Mysql')
        $("#port").val('3306');
    });
});
