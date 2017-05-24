<?php
	
	// Delete current record.
	function common_delete()
	{
		// Set up account info.
		$access_obj = new \dc\access\status();
		
		// Initialize database query object.
		$query 	= new \dc\yukon\Database();
		
		// Initialize main data class and populate it from
		// post variables. All we need is the ID, so
		// common data will work here.
		$_main_data = new \data\Common();						
		$_main_data->populate_from_request();
			
		// Call and execute delete SP.
		$query->set_sql('{call master_delete(@id = ?,													 
								@update_by	= ?, 
								@update_ip 	= ?)}');
		
		$params = array(array($_main_data->get_id(), 		SQLSRV_PARAM_IN),
					array($access_obj->get_id(), 			SQLSRV_PARAM_IN),
					array($access_obj->get_ip(), 			SQLSRV_PARAM_IN));
		
					
		$query->set_params($params);
		$query->query();	
		
		// Refrsh page.
		header('Location: '.$_SERVER['PHP_SELF']);
		
	}
?>