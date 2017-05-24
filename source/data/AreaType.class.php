<?php

	namespace data;
	
	interface iAreaType
	{
		// Accessors
		function get_selected();
		
		// Mutators
		function set_id($value);
		
		// Parent overloads 
		function set_sub_type($value);	
	}
	
	class AreaType extends Common implements iAreaType
	{	
		protected
			$value 		= NULL,
			$selected	= NULL;
	
		// Get and return an xml string for database use.
		public function xml()
		{
			$result = NULL;
			
			// If there are no rows at all in the html form, we send a
			// blank xml.
			if(is_array($this->value) == TRUE)
			{			
				$result = '<root>';
							
				foreach($this->value as $key => $value)
				{					
					$result .= '<row id="'.$value.'">';	
					$result .= '</row>';									
				}
				
				$result .= '</root>';
			}
			
			return $result;
		}	
		
		// Accessors
			public function get_selected()
			{
				return $this->selected;
			}
		
		
		// Overide conflicting functions from
		// parent class.
			public function set_id($value)
			{
			}
		
		// Mutators 
			public function set_sub_type($value)
			{
				$this->value = $value;
			}	
	}	
	
?>