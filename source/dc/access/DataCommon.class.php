<?php

	namespace dc\access;
	
	interface iDataCommon
	{
		// Operations
		function populate_from_request($target);
	}

	class DataCommon implements iDataCommon
	{	
		// Populate members from $_REQUEST.
		public function populate_from_request($target = NULL)
		{	
			// If there's no target, just default to self.
			if(!is_object($target))
			{
				$target = $this;
			}
			
			// Interate through each class method.
			foreach(get_class_methods($target) as $method) 
			{		
				$key = str_replace('set_', '', $method);
				
				//echo '<br />$key: '.$key;
							
				// If there is a request var with key matching
				// current method name, then the current method 
				// is a set mutator for this request var. Run 
				// it (the set method) with the request var. 
				if(isset($_REQUEST[$key]))
				{					
					$target->$method($_REQUEST[$key]);					
				
					//echo ', _REQUEST:'.$_REQUEST[$key];				
				}
			}			
		}
	}

?>