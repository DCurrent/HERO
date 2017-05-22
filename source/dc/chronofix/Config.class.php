<?php

	namespace dc\chronofix;
	
	require('config.php');

	// Implements default settings and user setting
	// arguments as necessary.
	// Structure of parameters used for database connection attempt.
	interface iConfig
	{	
		function get_format();			// Get current date/time format.
		function set_format($value);	// Set current date/time format.	
	}
	
	class Config implements iConfig
	{
		protected
			$format = NULL;
			
		public function __construct($format = NULL)
		{		
			// If format argument passed, use it for time
			// format. Otherwise fall back to default setting.
			if($format)
			{
				$this->set_format($format);		
			}
			else
			{
				$this->set_format(DEFAULTS::FORMAT); 
			}		
		}
		
		// Mutators
		public function set_format($value)
		{
			$this->format = $value;
		}
		
		public function get_format()
		{
			return $this->format;
		}	
	}

?>
