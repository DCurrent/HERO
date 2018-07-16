<?php
	
	require(__DIR__.'/config.php');				// User defined settings.
	require(__DIR__.'/navigation.php');	
	
	// Ensure we're using https.
	if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off")
	{
    	$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    	header('HTTP/1.1 301 Moved Permanently');
    	header('Location: ' . $redirect);
    	exit();
	}

	// Load class using namespace.
	function app_load_class($class_name) 
	{
        $file_name = '';
        $namespace = '';

		//echo '<!-- Class request: '.$class_name.' -->'.PHP_EOL;

        // Sets the include path as the "src" directory
        $include_path = __DIR__;

		// Find the string position of the last namespace separator (\) in class name.
		$lastNsPos = strripos($class_name, '\\');

		// If we found the namespace separator, let's build a 
		// file name string.
        if ($lastNsPos)
		{
			// Namespace is the portion of of class name starting
			// from 0 and ending at last namespace separator.
            $namespace = substr($class_name, 0, $lastNsPos);
			
			// Crop namespace from class name to leave only class name itself.
            $class_name = substr($class_name, $lastNsPos + 1);
			
			// Add directory separator to namespace to start a file path.
            $file_name = str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
        }
		
		// Add suffix to file name, then add include path to build
		// full file name path.
        $file_name .= $class_name.'.class.php';
        $file_name_full = $include_path . DIRECTORY_SEPARATOR . $file_name;
	   
	   	// If complete file path exists, then load it.
        if (file_exists($file_name_full)) 
		{
            require($file_name_full);
			
        	//echo ', loaded successfully. -->'.PHP_EOL;
		} 
		else 
		{
            //echo '<-- '.$file_name_full.' not found. -->'.PHP_EOL;
        }
    }
	
    spl_autoload_register('app_load_class');

	// Prepare default database configuration.
		// Establish connection configuration object.
		$yukon_connect_config = new \dc\yukon\ConnectConfig();
		
		// Use application defaults as connection arguments.
		$yukon_connect_config->set_host(DATABASE::HOST);
		$yukon_connect_config->set_name(DATABASE::NAME);
		$yukon_connect_config->set_user(DATABASE::USER);
		$yukon_connect_config->set_password(DATABASE::PASSWORD);

		// Open connection with configuration arguments.
		$yukon_connection 	= new \dc\yukon\Connect($yukon_connect_config);
		$yukon_database		= new \dc\yukon\Database($yukon_connection);

	// Prepare common entry configuration.
		$common_entry_config = new \dc\application\CommonEntry($yukon_connection);
	

	// Replace PHPs default session handler.
		// Prepare session handler configuration.
		$session_config = new \dc\nahoni\SessionConfig();
		$session_config->set_database($yukon_database);
		$session_config->set_sp_prefix(APPLICATION_SETTINGS::SESSION_PREFIX);

		$session_handler = new \dc\nahoni\Session($session_config);
		session_set_save_handler($session_handler, TRUE);	
	
	
		
?>