<?php
	function common_save($yukon_database)
	{
		
		// Set up account info.
		$access_obj = new \dc\stoeckl\status();
				
		// Initialize main data class and populate it from
		// post variables.
		$_main_data = new \data\Area();						
		$_main_data->populate_from_request();
			
		// Call update stored procedure.
		$yukon_database->set_sql('{call '.LOCAL_STORED_PROC_NAME.'_update(@id			= ?,
												@log_update_by	= ?, 
												@log_update_ip 	= ?,										 
												@label 			= ?,
												@details 		= ?)}');
												
		$params = array(array('<root><row id="'.$_main_data->get_id().'"/></root>', 		SQLSRV_PARAM_IN),
					array($access_obj->get_id(), 				SQLSRV_PARAM_IN),
					array($access_obj->get_ip(), 			SQLSRV_PARAM_IN),
					array($_main_data->get_label(), 		SQLSRV_PARAM_IN),						
					array($_main_data->get_details(),		SQLSRV_PARAM_IN));
		
		$yukon_database->set_param_array($params);			
		$yukon_database->query_run();
		
		// Repopulate main data object with results from merge query.
		// We can use common data here because all we need
		// is the ID for redirection.
		$yukon_database->get_line_config()->set_class_name('\data\Common');
		$_main_data = $query->get_line_object();
		
		// Now that save operation has completed, reload page using ID from
		// database. This ensures the ID is always up to date, even with a new
		// or copied record.
		header('Location: '.$_SERVER['PHP_SELF'].'?id='.$_main_data->get_id()); 
	}
?>