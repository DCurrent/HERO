<?php

	namespace data;
	
	interface iAccountRole
	{
		// Accessors
			function get_role();
			
		// Mutators
			function set_id($value);
			function set_role($value);
			function set_sub_role_id($value);
			function set_sub_role_role($value);
			
		// Operators
			function xml();
	}
	
	class AccountRole extends Common implements iAccountRole
	{
		private
			$role		= NULL;
		
		// Get and return an xml string for database use.
		public function xml()
		{
			$result = '<root>';
			
			if(is_array($this->id) === TRUE)			
			{
				foreach($this->id as $key => $id)
				{								
					$result .= '<row id="'.$this->role[$key].'" />';									
				}			
			}
			
			$result .= '</root>';
			
			return $result;
		}
		
		// Accessors
		public function get_role()
		{
			return $this->role;
		}
		
		// Mutators
		// "sub" mutators prevent multiple instances of the same
		// data member name from different classes on a form
		// from interfering with each other.
		public function set_role($value)
		{
		}
		
		public function set_id($value)
		{
		}

		public function set_sub_role_id($value)
		{			
			$this->id = $value;			
		}
							
		public function set_sub_role_role($value)
		{
			$this->role = $value;			
		}
	}

?>