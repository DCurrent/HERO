<?php

	namespace dc\access;
	
	interface iDataAccount
	{
		// Accessors
		function get_id();	
		function get_account();		
		function get_name_f();		
		function get_name_m();		
		function get_name_l();		
		function get_credential();		
		function get_account_id();		
		function get_email();
		
		// Mutators
		function set_DataCommon(DataCommon $value);
		function set_id($value);	
		function set_account($value);		
		function set_name_f($value);		
		function set_name_m($value);		
		function set_name_l($value);		
		function set_credential($value);		
		function set_account_id($value);		
		function set_email($value);		
		
		// Operations
		function clear();			// Clear data members and account session data.
		function name_full();		// Return a first and last name.
		function name_proper();		// Return a proper name.
		function session_save();	// Send data members to session.
		function session_get();		// Populate data members from session.
		function populate_from_request();
	}
	
	class DataAccount implements iDataAccount
	{	
		private
			$data_common	= NULL,	// Object for common data members and operations.
			$id				= NULL,	// ID as assigned by application account database.
			$account		= NULL,		
			$account_id		= NULL,	// Natural ID - i.e., given by organization.
			$credential		= NULL,
			$email			= NULL,
			$name_f			= NULL,
			$name_l			= NULL,
			$name_m			= NULL;			
		
		function __construct(DataCommon $data_common = NULL)
		{
			// Use argument or create new object if NULL.
			if(is_object($data_common))
			{
				$this->set_DataCommon($data_common);
			}
			else
			{
				$data_common = new DataCommon();
				
				$this->set_DataCommon($data_common);
			}
			
		}
		
		// Send account data to session.
		public function session_save()
		{
			$_SESSION[SES_KEY::ID] 			= $this->id;
			$_SESSION[SES_KEY::ACCOUNT] 		= $this->account;
			$_SESSION[SES_KEY::ACCOUNT_ID]	= $this->account_id;
			$_SESSION[SES_KEY::EMAIL]		= $this->email;
			$_SESSION[SES_KEY::NAME_F]		= $this->name_f;
			$_SESSION[SES_KEY::NAME_L]		= $this->name_l;
			$_SESSION[SES_KEY::NAME_M]		= $this->name_m;
		}
		
		// Get account data from session.
		public function session_get()
		{
			if(isset($_SESSION[SES_KEY::ID])) 			$this->id 			= $_SESSION[SES_KEY::ID];
			if(isset($_SESSION[SES_KEY::ACCOUNT])) 		$this->account 		= $_SESSION[SES_KEY::ACCOUNT];
			if(isset($_SESSION[SES_KEY::ACCOUNT_ID]))	$this->account_id	= $_SESSION[SES_KEY::ACCOUNT_ID];
			if(isset($_SESSION[SES_KEY::EMAIL])) 		$this->email 		= $_SESSION[SES_KEY::EMAIL];
			if(isset($_SESSION[SES_KEY::NAME_F])) 		$this->name_f 		= $_SESSION[SES_KEY::NAME_F];
			if(isset($_SESSION[SES_KEY::NAME_L])) 		$this->name_l 		= $_SESSION[SES_KEY::NAME_L];
			if(isset($_SESSION[SES_KEY::NAME_M])) 		$this->name_m 		= $_SESSION[SES_KEY::NAME_M];
		}
		
		// Clear all data members.
		public function clear()
		{
			// Clear data members.	
			foreach ($this as $key => $value) 
			{
				$this->$key = NULL;
			}	
			
			// Record empty data members to session.
			$this->session_save();
		}
		
		public function name_full()
		{
			return $this->get_name_f().' '.$this->get_name_l();
		}
		
		public function name_proper()
		{
			$result = NULL;
			
			// Last name found? Ad last name.
			if($this->get_name_l()) $result .= $this->get_name_l();
			
			// First name found?
			if($this->get_name_f())
			{
				// Last name found? If so we add the comma. Otherwise just first name.
				if($this->get_name_l()) $result .= ', ';
				
				$result .= $this->get_name_f();							
			}
			
			// Middle name found? Add it as well.
			//if($this->get_name_m()) $result .= ' '.$this->get_name_m();		
			
			return $result;			
		}
		
		public function populate_from_request()
		{		
			$this->DataCommon->populate_from_request($this);
		}
		
		public function get_id()
		{
			return $this->id;
		}
		
		public function get_account()
		{
			return $this->account;
		}
				
		public function get_name_f()
		{
			return $this->name_f;
		}
		
		public function get_name_m()
		{
			return $this->name_m;
		}
		
		public function get_name_l()
		{
			return $this->name_l;
		}
		
		public function get_credential()
		{
			return $this->credential;
		}
		
		public function get_account_id()
		{
			return $this->account_id;
		}
		
		public function get_email()
		{
			return $this->email;
		}
		
		public function set_DataCommon(DataCommon $value)
		{
			$this->DataCommon = $value;
		}
		
		public function set_id($value)
		{
			$this->id = $value;
		}
		
		public function set_credential($value)
		{
			$this->credential = $value;
		}
		
		public function set_account($value)
		{
			$this->account = $value;
		}
		
		public function set_name_f($value)
		{
			$this->name_f = $value;
		}
		
		public function set_name_m($value)
		{
			$this->name_m = $value;
		}		
		
		public function set_name_l($value)
		{
			$this->name_l = $value;
		}
		
		public function set_account_id($value)
		{
			$this->account_id = $value;
		}
		
		public function set_email($value)
		{
			$this->email = $value;
		}
	}
?>