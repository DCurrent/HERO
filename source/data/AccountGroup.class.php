<?php

	namespace data;

	interface iAccountGroup
	{
		//  Accessors.
		function get_type();
		
		//  Mutators
		function set_type($value);
	}
	
	class AccountGroup extends Common implements AccountGroup
	{
		private
			$type = NULL;
			
		public function get_type()
		{
			return $this->type;
		}
		
		public function set_type($value)
		{
			$this->type = $value;
		}
	}
?>