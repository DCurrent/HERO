<?php

	namespace dc\access;

	interface iprocess
	{		
		// Accessors.
		function get_DataAccount();
		function get_feedback();
		function get_login_result();
		function get_redirect();
		function get_config();
		
		// Mutators
		function set_access_action($value);
		function set_authenticate_url($value);
		function set_data_account($value);	
		function set_redirect($value);
		
		// Operations.
		function action();
		function dialog();
		function login_application();
		function login_ldap();		
		function login_local();
		function logoff();
		function populate_from_request();
		function process_control();		
	}

	// process log on and log off.
	class process implements iprocess
	{
		private
			$action			= NULL,
			$data_account	= NULL,	// Object containing acount data (name, account etc.)
			$data_common	= NULL,	// Object containing basic data and operations.
			$login_result	= NULL, // Result of login attempt.
			$config 		= NULL,	// config object.
			$feedback		= NULL, // Feedback.
			$redirect		= NULL;	// URL user came from and should be sent back to after login.
			
		public function __construct(config $config = NULL, DataCommon $data_common = NULL, DataAccount $data_account = NULL)
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
			
			// Use argument or create new object if NULL.
			if(is_object($data_common) === TRUE)
			{		
				$this->DataCommon = $data_common;			
			}
			else
			{
				$this->DataCommon = new DataCommon();		
			}
			
			// Use argument or create new object if NULL.
			if(is_object($data_account) === TRUE)
			{		
				$this->data_account = $data_account;			
			}
			else
			{
				$this->data_account = new DataAccount();			
			}

			session_start();

			if(isset($_SESSION[SES_KEY::REDIRECT])) $this->redirect = $_SESSION[SES_KEY::REDIRECT];		
		}	
		
		// Populate members from $_REQUEST.
		public function populate_from_request()
		{
			
			
			$this->DataCommon->populate_from_request($this);	
		}	
		
		public function dialog()
		{
			if(isset($_SESSION[SES_KEY::DIALOG]))
			{
				return $_SESSION[SES_KEY::DIALOG];
			}
		}
		
		public function get_redirect()
		{
			return $this->redirect;
		}
		
		public function get_login_result()
		{
			return $this->login_result;
		}
		
		public function get_feedback()
		{
			return $this->feedback;
		}
		
		public function get_config()
		{
			return $this->config;
		}
		
		public function get_DataAccount()
		{
			return $this->data_account;
		}
		
		public function set_redirect($value)
		{
			// Temporarly disabled so we don't kill redirect. Will need to
			// change namgin convention for mutators to get around this.
			
			//$this->redirect = $value;
		}
		
		public function set_authenticate_url($value)
		{
			$this->config->set_authenticate_url($value);
		}
		
		public function set_data_account($value)
		{
			$this->data_account = $value;
		}
			
		public function set_access_action($value)
		{
			$this->action = $value;
		}
		
		// Shortcut controller for login process.
		public function process_control()
		{
			$this->populate_from_request();
			
			//echo '<br />$this->action: '.$this->action;
			
			switch($this->action)
			{
				case ACTION::LOGIN;				
					
					// Populate account data from request.
					$this->data_account->populate_from_request();
					
					// First try local.
					$this->login_local();
									
					// If local fails, try LDAP.
					if($this->login_result != LOGIN_RESULT::LOCAL)
					{
						$this->login_ldap();
					}
					
					$this->action();
					break;
					
				case ACTION::LOGOFF;
					
					$this->logoff();
					break;
			}
		}
		
		// Take action based on result of login attempt.
		public function action()
		{		
			//echo '<br />$this->login_result: '.$this->login_result;
			
			switch($this->login_result)
			{				
				case LOGIN_RESULT::LOCAL:      			
				case LOGIN_RESULT::LDAP:			
				
					
				
					// Get account information from
					// application database.
					$this->login_application();
				
					
				
					// Record client information information into session.
					$this->data_account->session_save();				
					
					
						
					// Set dialog.					
					// Start caching page contents.
					ob_start();
					?>
						<span class="text-success">Hello <?php echo $this->data_account->get_name_f(); ?>, your log in was successful.</span>	
					<?php
					
					// Collect contents from cache and then clean it.
					$_SESSION[SES_KEY::DIALOG] = ob_get_contents();
					ob_end_clean();	
					
					// Redirect URL passed?		
					if($this->redirect)
					{					
						// If headers are not sent, redirect to user requested page.
						if(headers_sent())
						{							
						}
						else
						{						
							header('Location: '.$this->redirect);
						}				
					}
					
					break;
				
				case LOGIN_RESULT::NO_BIND:
				
					// Set dialog.					
					// Start caching page contents.
					ob_start();
					?>
						<span class="text-danger">Bad user name or password.</span>	
					<?php
					
					// Collect contents from cache and then clean it.
					$_SESSION[SES_KEY::DIALOG] = ob_get_contents();
					ob_end_clean();
					
					break;			
				
				case LOGIN_RESULT::NO_INPUT:
				default: 				
				
					// Default log in dialog.
					$_SESSION[SES_KEY::DIALOG] = NULL;
			}
		}
		
		// Log the current user.
		public function logoff()
		{	
			// Remove all session data.
			session_unset();
			
			if(session_status() === PHP_SESSION_ACTIVE)
			{
				session_destroy();
			}
			
			// Clear account object data.
			$this->data_account->clear();
			
									
			// If headers are not sent, redirect to the authenticate url.
			if(headers_sent())
			{						
			}
			else
			{						
				header('Location: '.$this->config->get_authenticate_url());
			}		
		}
		
		// login_ldap
		// Caskey, Damon V.
		// 2012-02-03
			
		// Process login attempt.	
		public function login_ldap()
		{		
			//echo '<br /> login_ldap';
						
			$principal		= NULL;		// Active directory principal (account).
			$result			= NULL;		// Active directory account search result.
			$entries		= NULL;		// Active directory entry array.
			
			$bind			= FALSE;	// Result of bind attempt.
			$ldap			= NULL;		// ldap connection reference.
			
			$req_account	= NULL;
			$req_credential	= NULL;					
								
			// Get values.
			$req_account 			= $this->data_account->get_account();
			$req_credential			= $this->data_account->get_credential();				
				
			// User provided credentials? 
			if ($req_account != NULL && $req_credential != NULL)
			{
											
				// Attempt to bind user through LDAP using all known domain prefixes.
				$bind = $this->ldap_bind_check();
				
				// If we were able to bind user through AD LDAP, we will then run search in EDIR to get their basic information. 
				// Otherwise the account doesn't exist or user entered bad credentials. 
				if($bind === TRUE)
				{									
					// Connect to LDAP EDIR.
					$ldap = ldap_connect($this->config->get_ldap_host_dir());
					
					if(!$ldap) trigger_error("Cannot connect to LDAP: ".$this->config->get_ldap_host_dir(), E_USER_ERROR); 
					
					// Search for account name.
					$result = ldap_search($ldap, $this->config->get_ldap_base_dn(), 'uid='.$req_account);			
					
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
					
					// Save account data into session.
					$this->data_account->session_save();
					
					$this->login_result = LOGIN_RESULT::LDAP;			
									
					// Release ldap query result.
					ldap_free_result($result);		
										
					// Close ldap connection.
					ldap_close($ldap);									
				}
				else // No Bind.
				{
					$this->login_result = LOGIN_RESULT::NO_BIND;
				}														
			}
					
			// Return results.
			return $this->login_result;		
		}
		
		// ldap_bind_check
		// Caskey, Damon V.
		//	2013-11-13
		//	~2015-07-19
			
		//	Attempt to bind ldap adding all possible prefixes.
		private function ldap_bind_check()
		{
			
			$result			= FALSE;	// Final result.
			$account		= NULL;		// Prepared account string to attempt bind.
			$prefix_list 	= array();
			$prefix 		= NULL;		// Singular prefix value taken from array.
			$ldap 			= NULL;
			
			$ldap = ldap_connect($this->config->get_ldap_host_bind());
			if(!$ldap) trigger_error("Cannot connect to LDAP: ".$this->config->get_ldap_host_bind(), E_USER_ERROR);
									
			ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
			
			// Dereference account name and remove any domain prefixes. We'll add our own below.
			$account = str_ireplace($prefix, '', $account);
			
			//$prefix_list = explode(',', $this->config->get_dn_prefix());
			$prefix_list = array(NULL, 'ad/', 'ad\\', 'mc/', 'mc\\');
						
			// Keep trying prefixes until there is a bind or we run out.
			foreach($prefix_list as $prefix)
			{		
				$account = $prefix.$this->data_account->get_account();
				
				// Attempt to bind with account (prefix included) and password.
				$result = @ldap_bind($ldap, $account, $this->data_account->get_credential());
				
				// If successfull bind.
				if($result === TRUE) break;
			}	
			
			// Close ldap connection.
			ldap_close($ldap);				
					
			// Return results.
			return $result;
		}
		
		
		// Process login attempt through local database.
		public function login_local()
		{					
			// Get values.
			$req_account 			= $this->data_account->get_account();
			$req_credential			= $this->data_account->get_credential();						
			
			$query = new \dc\yukon\Database();			
		
			// Query the local account table using given account and password.
			$query->set_sql("{call account_login(@account 		= ?,														 
												@credential 	= ?)}");				
			
			$params = array(&$req_account, &$req_credential);
			
			$query->set_params($params);		
			$query->query();
			
			// If a row is returned, then provided credentials match a local login.
			if($query->get_row_exists())
			{
				// Populate account data object with datbase row.
				$query->get_line_params()->set_class_name(__NAMESPACE__.'\DataAccount');					
				$this->data_account = $query->get_line_object();
				
				// Email is not in the data base as a field, but accounts
				// ARE email, so just transpose it here.
				$this->data_account->set_email($this->data_account->get_account());
				
				// Set result to indicate a local login.				 														
				$this->login_result = LOGIN_RESULT::LOCAL;									
			}
			else
			{				
				$this->login_result = LOGIN_RESULT::NO_BIND;
			}
		}
		
		// Get account information from application specific database.
		public function login_application()
		{					
			// Get values.
			$account = $this->data_account->get_account();
			
			$query = new \dc\yukon\Database();			
		
			// Query the local account table using given account and password.
			$query->set_sql("{call stf_account_lookup(@account = ?)}");				
			
			$params = array($account);
			
			$query->set_params($params);		
			$query->query();
			
			// If a row is returned, then provided credentials match a local login.
			if($query->get_row_exists())
			{
				// Populate account data object with datbase row.
				$query->get_line_params()->set_class_name(__NAMESPACE__.'\DataAccount');					
				$this->data_account = $query->get_line_object();									
			}
			else
			{				
			}
		}	
	}

	

?>