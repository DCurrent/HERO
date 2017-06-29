<?php
	
	require(__DIR__.'/config.php');				// User defined settings.
	require(__DIR__.'/navigation.php');	
	
	// Load class using namespace.
	function portland_load_class($class_name) 
	{
        $file_name = '';
        $namespace = '';

		echo '<!-- Class request: '.$class_name.' -->'.PHP_EOL;

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
            echo '<-- '.$file_name_full.' not found. -->'.PHP_EOL;
        }
    }
	
    spl_autoload_register('portland_load_class');
	
	// Replace default session handler.
	$session_handler = new \dc\nahoni\Session();
	session_set_save_handler($session_handler, TRUE);
	
	
		
?>