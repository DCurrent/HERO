<?php

	namespace dc\application;

	require_once('config.php');

	interface iCommonEntryConfig
	{
		// Accessors
		function get_config();				// Get current layout object.
		function get_data_common_entry();
		
		// Mutators
		function set_data_common_entry($value);
		
		// Operations
		function create_config_object();	// Build layout object from database.
		
	}

	class CommonEntryConfig implements iCommonEntryConfig
	{
		private
			$config_object 	= NULL,
			$data_common_entry	= NULL;
			
		public function __construct(CommonEntry $data_common_entry = NULL)
		{
			// If no common data object provided, create
			// a new copy.
			if(!is_object($data_common_entry))
			{
				$data_common_entry = new CommonEntry();
			}
			
			$this->set_data_common_entry($data_common_entry);
		}
		
		// Accessors
		function get_config()
		{
			return $this->config_object;	
		}
		
		function get_data_common_entry()
		{
			return $this->data_common_entry;
		}
		
		// Mutators
		function set_data_common_entry($value)
		{
			$this->data_common_entry = $value;
		}
		
		public function create_config_object()
		{
			$result = NULL;
			
			// Populate with request data - this is mainly to get
			// the "id_form" from URL query string.
			$this->data_common_entry->populate_from_request();
			
			// Initialize database query object.
			$database 	= new \dc\yukon\Database();
			
			// Set up primary query with parameters and arguments.
			$database->set_sql('{call '.DEFAULTS::PROCEDURE.'(@param_filter_id = ?)}');
										
			$params = array(array($this->data_common_entry->get_id_form(), SQLSRV_PARAM_IN));	
						
			// Apply arguments and execute query.
			$database->set_params($params);
			$database->query();
			
			// Skip navigation data and get primary data record set.	
			$database->get_next_result();
			
			$database->get_line_params()->set_class_name(__NAMESPACE__.'\CommonEntry');	
			
			if($database->get_row_exists() === TRUE) 
			{
				$result = $database->get_line_object();
			}
			else
			{
				$result = new CommonEntry();
			}
			
			$this->config_object = $result;
						
			return $result;
		}		
	}
?>