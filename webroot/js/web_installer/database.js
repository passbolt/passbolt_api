document.addEventListener('DOMContentLoaded', () => {
  const schemaBlock = document.getElementById('schema-block');
  const port = document.getElementById('port');
  const driver = document.getElementById('driver');

  /**
   * Update port and schema visibility based on selected database driver.
   */
  const handleDatabaseDriver = () => {
    switch (driver.value) {
      case 'Cake\\Database\\Driver\\Postgres':
        port.value = '5432';
        schemaBlock.classList.remove('hidden');
        break;
      case 'Cake\\Database\\Driver\\Mysql':
        port.value = '3306';
        schemaBlock.classList.add('hidden');
        break;
      default:
        break;
    }
  };

  handleDatabaseDriver();
  driver.addEventListener('change', handleDatabaseDriver);
});
