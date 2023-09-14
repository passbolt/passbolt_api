$(function() {
  /**
   * Detect id the driver is Postgres.
   */
  let handleDatabaseDriver = function() {
    let schema = $("#schema-block");
    let port = $("#port");
    switch ($("#driver").val()) {
      case 'Cake\\Database\\Driver\\Postgres':
        port.val('5432');
        schema.show();
        break;
      case 'Cake\\Database\\Driver\\Mysql':
        port.val('3306');
        schema.hide();
        break;
      default:
        break;
    }
  };

  handleDatabaseDriver();
  $("#driver")
    .chosen({width: '100%', disable_search: true})
    .change(function () {
      handleDatabaseDriver();
    });
});
