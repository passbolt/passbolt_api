<?php
class Installation extends AppModel {
	var $name = 'Installation';
	
	function createDb($dbname, $dbusername, $dbpassword){
		$db = &ConnectionManager::getDataSource('admin');
		$db->query("CREATE USER '$dbusername'@'%' IDENTIFIED BY '$dbpassword'");
		$db->query("GRANT USAGE ON * . * TO '$dbusername'@'%' IDENTIFIED BY '$dbpassword' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0");
		$db->query("CREATE DATABASE IF NOT EXISTS `$dbname` ");
		$db->query("GRANT ALL PRIVILEGES ON `$dbname` . * TO '$dbusername'@'%'");	

		return true;
	}
	
	function createTables($dbname, $dbusername, $dbpassword){
		// Set correct database name
		$config['driver'] = 'mysql';
		$config['persistent'] = false;
		$config['host'] = 'localhost';
		$config['login'] = $dbusername;
		$config['password'] = $dbpassword;
		$config['database'] = $dbname;
		$config['prefix'] = '';
		
		$db = $this->getDbConnection($dbname, $config);
		
		$fileName = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR .'webroot'. DIRECTORY_SEPARATOR .'files'. DIRECTORY_SEPARATOR .'install'. DIRECTORY_SEPARATOR .'db_structure.sql';
		$statements = file_get_contents($fileName);
        $statements = explode(';', $statements);
        
        foreach ($statements as $statement) {
            if (trim($statement) != '') {
                $db->query($statement);
            }
        }
	}
	
}