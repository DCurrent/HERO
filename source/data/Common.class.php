<?php

	namespace data;
	
	// Common data. Contains common
	// data members and methods that would
	// otherwise be repeated in virtually
	// every other class. 
	//
	// Note that when dealing with sub
	// data, overloading some of the
	// set_<>() methods will be nessesary
	// to avoid conflicts.
	interface iCommon
	{
		// Accessors
		function get_details();
		function get_item();
		function get_label();
		function get_address();
		
		// Mutators
		function set_details($value);
		function set_item($value);
		function set_label($value);
		function set_address($value);
		
		// Operations
		
		// Populate data members from $_REQUEST vars.
		function populate_from_request($target); 
	}
	
	// See interface for notes.
	class Common extends Master implements iCommon
	{
		protected
			$address			= NULL,
			$details			= NULL,
			$item				= NULL,	// Used to link entry to a specfic item in a seperate list.
			$label				= NULL;
		
		// Populate members from $_REQUEST.
		public function populate_from_request($target = NULL)
		{	
			$methods	= NULL;
			$method		= NULL;
			$key		= NULL;
			
			//var_dump($_REQUEST);
			
			if(!is_object($target))
			{
				$target = $this;
			}
			
			// Get the array of methods for target.
			$methods = get_class_methods($target);
			
			// Iterate through each class method.
			foreach($methods as $method) 
			{
				
				//echo '<br />'.$method;
				
				// Remove 'set_' from $key string, so for
				// example, "set_id" becomes "id". 	
				$key = str_replace('set_', '', $method);
				
				// Look for a $_REQUEST var with our new
				// $key string. If we find one, that means
				// the class method in this loop iteration
				// is a match. We can run the method and 
				// pass it the $_REQUEST var (using $key string)
				// as its value argument. 
				if(isset($_REQUEST[$key]))
				{	
					
					// Debugging
					 //echo '<br /><br />'.$method.'<br />';					
					 //var_dump($_REQUEST[$key]);
					
					$target->$method($_REQUEST[$key]);					
				}
			}			
		}		
		
		public function get_address()
		{
			return $this->address;
		}
		
		public function get_label()
		{
			return $this->label;
		}
		
		public function get_item()
		{
			return $this->item;
		}
		
		public function get_details()
		{
			return $this->details;
		}
	
		public function set_address($value)
		{
			$this->address = $value;
		}
		
		public function set_label($value)
		{
			$this->label = $value;	
		}
		
		public function set_details($value)
		{
			$this->details = $value;
		}
		
		public function set_item($value)
		{
			$this->item = $value;
		}
	}
	
	
?>
