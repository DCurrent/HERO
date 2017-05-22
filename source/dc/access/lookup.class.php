<?php

	namespace dc\access;

	interface ilookup
	{
		// Accessors.
		function get_config();
		function get_DataAccount();		
		
		// Mutators.
		function set_data_account($value);			
		
		// Operations.
		function lookup($account); // Performs the user lookup against LDAP on a login attempt.
	}

	class lookup
	{
		private
			$action	= NULL,
			$data_account	= NULL,	// Object containing acount data (name, account etc.)
			$login_result	= NULL, // Result of login attempt.
			$config 		= NULL,	// config object.
			$feedback		= NULL, // Feedback.
			$redirect		= NULL;	// URL user came from and should be sent back to after login.
			
		public function __construct(config $config = NULL)
		{
			// Use argument or create new object if NULL.
			if(is_object($config) === TRUE)
			{		
				$this->config = $config;			
			}
			else
			{
				$this->config = new config();			
			}
			
			$this->data_account = new DataAccount();		
		}	
		
		public function get_config()
		{
			return $this->config;
		}
		
		public function get_DataAccount()
		{
			return $this->data_account;
		}
		
		public function set_data_account($value)
		{
			$this->data_account = $value;
		}
			
		public function lookup($account)
		{		
			/*
			login
			Damon Vaughn Caskey
			2012-02-03
			
			Process login attempt.		
			*/
						
			$result			= NULL;		// Active directory account search result.
			$entries		= NULL;		// Active directory entry array.
			
			$bind			= FALSE;	// Result of bind attempt.
			$ldap			= NULL;		// ldap connection reference.
				
			// No account? Get out before we cause a nasty error.
			if ($account === NULL) return;
									
			// Connect to LDAP EDIR.
			$ldap = ldap_connect($this->config->get_ldap_host_dir());
			
			if(!$ldap) trigger_error("Cannot connect to LDAP: ".$this->config->get_ldap_host_dir(), E_USER_ERROR); 
			
			// Search for account name.
			$result = ldap_search($ldap, $this->config->get_ldap_base_dn(), 'uid='.$account);			
			
			// Trigger error if no result located.			
			if (!$result) trigger_error("Could not locate entry in EDIR.", E_USER_ERROR);
			
			// Get user info array.
			$entries = ldap_get_entries($ldap, $result);
			
			// Trigger error if entries array is empty.
			if($entries["count"] < 0) trigger_error("Entry found but contained no data.", E_USER_ERROR);
			
			// Populate account object members with user info.
			if(isset($entries[0]['cn'][0])) 			$this->data_account->set_account($entries[0]['cn'][0]);
			if(isset($entries[0]['givenname'][0])) 		$this->data_account->set_name_f($entries[0]['givenname'][0]);
			if(isset($entries[0]['initials'][0]))		$this->data_account->set_name_m($entries[0]['initials'][0]);
			if(isset($entries[0]['sn'][0]))				$this->data_account->set_name_l($entries[0]['sn'][0]);					
			if(isset($entries[0]['workforceid'][0]))	$this->data_account->set_account_id($entries[0]['workforceid'][0]);
			if(isset($entries[0]['mail'][0]))			$this->data_account->set_email($entries[0]['mail'][0]);				
									
			// Release ldap query result.
			ldap_free_result($result);		
								
			// Close ldap connection.
			ldap_close($ldap);						
		}			
	}

	

?>