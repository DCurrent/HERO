<?php
	
	namespace data;

	interface iAccount
	{
		// Accessors.
		function get_account();
		function get_department();
		function get_name_f();		// First name.		
		function get_name_l();		// Last name.
		function get_name_m();		// Middle name.
		function get_status();		
		
		// Mutators.
		function set_account($value);
		function set_department($value);
		function set_name_f($value);		// First name.		
		function set_name_l($value);		// Last name.
		function set_name_m($value);		// Middle name.
		function set_status($value);			
	}
	
	class Account extends Common implements iAccount
	{
		protected			
			$account	= NULL,	
			$department	= NULL,
			$name_f		= NULL,			
			$name_l		= NULL,
			$name_m		= NULL,
			$status		= NULL;	// UK Status
			
		// Accessors					
		public function get_account()
		{
			return $this->account;
		}
		
		public function get_department()
		{
			return $this->department;
		}
		
		public function get_name_f()
		{
			return $this->name_f;
		}		
		
		public function get_name_l()
		{
			return $this->name_l;
		}
		
		public function get_name_m()
		{
			return $this->name_m;
		}
		
		public function get_status()
		{
			return $this->status;
		}
		
		// Mutators
		public function set_account($value)
		{
			$this->account = $value;
		}
		
		public function set_department($value)
		{
			$this->department = $value; 
		}
		
		public function set_name_f($value)
		{
			$this->name_f = $value;
		}
		
		public function set_name_l($value)
		{
			$this->name_l = $value;
		}
		
		public function set_name_m($value)
		{
			$this->name_m = $value;
		}
		
		public function set_status($value)
		{
			$this->status = $value;
		}				
	}	
	
?>