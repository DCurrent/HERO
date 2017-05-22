<?php
	
	namespace dc\access;

	interface istatus
	{
		// Accessors			
		function get_account();
		function get_list();
		function get_authorized();	
		function get_id();
		function get_ip();	
		function get_name_f();	
		function get_name_l();
		function get_name_m();	
		function get_redirect();
		function get_config();
				
		// Mutators	
		function set_list($value);				
		function set_authorized($value);
		function set_redirect($value);	
		function set_config(config $value);				
											
		// Operations
		function action();	
		function dialog();	
		function name_full();
		function name_proper();
		function verify();
	}
	
	class status implements istatus
	{
		private
			$data_account	= NULL,
			$list	= array(),
			$authorized		= NULL,
			$redirect		= NULL,
			$config		= NULL;
			
		
		public function dialog()
		{
			if(isset($_SESSION[SES_KEY::DIALOG]))
			{
				return $_SESSION[SES_KEY::DIALOG];
			}
		}
		
		public function get_account()
		{
			return $this->data_account->get_account();
		}
		
		public function get_id()
		{
			return $this->data_account->get_id();
		}
		
		public function get_config()
		{
			return $this->config;
		}
		
		public function get_list()
		{
			return $this->list;
		}
		
		public function get_authorized()
		{
			return $this->authorized;
		}
		
		public function get_name_f()
		{
			return $this->data_account->get_name_f();
		}
		
		public function get_name_l()
		{
			return $this->data_account->get_name_l();
		}
		
		public function get_name_m()
		{
			return $this->data_account->get_name_m();
		}
		
		public function get_redirect()
		{
			return $this->redirect;
		}
		
		public function get_ip()
		{
			return $_SERVER['REMOTE_ADDR'];
		}
		
		// Mutators	
		public function set_config(config $value)
		{
			$this->config = $value;
		}
		
		public function set_list($value)
		{
			$this->list = $value;
		}
		
		public function set_authorized($value)
		{
			$this->authorized = $value;
		}
		
		public function set_redirect($value)
		{
			$this->redirect = $value;
		}
					
		public function __construct(config $config = NULL, DataAccount $data_account = NULL)
		{
			// Make sure we have an active session
			if(session_status() == PHP_SESSION_NONE)
			{			
				session_start();
			}
			
			// If config object provided, we'll use it. Otherwise
			// create a new object with default values.
			if(is_object($config) === TRUE)
			{		
				$this->config = $config;			
			}
			else
			{
				$this->config = new config();			
			}
			
			// Use argument or create new object if NULL.
			if(is_object($data_account))
			{
				$this->data_account = $data_account;
			}
			else
			{
				$data_account = new \dc\access\DataAccount();
				
				$this->data_account = $data_account;
			}
			
			// Account session get.
			$this->data_account->session_get();
			
			// Default redirect to calling script.
			if(isset($_SESSION[SES_KEY::REDIRECT]))
			{
				$this->redirect = $_SESSION[SES_KEY::REDIRECT];
			}
			else
			{			
				$this->redirect = $_SERVER['PHP_SELF'];					
			}
		}
		
		public function name_full()
		{
			return $this->data_account->name_full();
		}
		
		public function name_proper()
		{
			return $this->data_account->name_proper();
		}
		
		private function is_guid($guid)
		{
			$result;
			
			$result = !empty($guid) && preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $guid);
			
			return TRUE;
		}
		
		public function verify()
		{	
			/*
			Damon Vaughn Caskey
			verify
			2015-07-16
			~Redesigned from verify created 2011-11-10
			
			Verify user is logged into session with AD account, and is part of authorized group.
			*/		
			
			$val			= NULL;								// Value extracted from array loop.
			$result 		= AUTHORIZED_RESULT::NO;		// Authorized?
			$list	= NULL;								// List of authorized users.
			$item	= NULL;								// Indivdiual item from list.	
						
			// Verify account is set at all. If so, then evaluate against provided list (if any).			
			if($this->data_account->get_account() === NULL)											
			{	
				$result	= AUTHORIZED_RESULT::NONE;
			}
			else
			{	
				
				// Defreference access list.
				$list = $this->list;
				
				// Is list a double linked list object?
				if(is_object($list) === TRUE)
				{
					// Loop over each item in double linked list and look for a match vs.
					// current account.
					for($list->rewind(); $list->valid(); $list->next())
					{						
						$item = $list->current();
						
						// If current account matches an item in the list, we can allow access.
						if($item->get_account() === $this->data_account->get_account() || $item->get_account() === DEFAULTS::ADMINISTRATOR)			
						{
							// Set result to allow access and break out of loop.
							$result = AUTHORIZED_RESULT::YES;
							break;	
						}
					}
				}
				else
				{
					// No list of users provided, so we allow access to any legit user.
					$result = AUTHORIZED_RESULT::YES;	
				}				
			}
			
			$this->authorized = $result;
				
			// Return result.
			return $result;
		}	
		
		public function action()
		{		
			// Now we'll take action based on authorization result.
			switch ($this->authorized)
			{
				// Client is logged in with sufficiant access.
				case AUTHORIZED_RESULT::YES:			
					
					break;
				
				// Client is logged in but lacks sufficiant access.
				case AUTHORIZED_RESULT::NO:				
					
					// Start caching page contents.
					ob_start();
					?>
						<span class="text-warning">We're sorry <?php echo $this->name_f_m; ?>, but you are not permitted to access this resource. Please log in with an authorized account.</span>	
					<?php
					
					// Collect contents from cache and then clean it.
					$_SESSION[SES_KEY::DIALOG] = ob_get_contents();
					ob_end_clean();			
				
					
				
				// No client is logged in.	
				default:
				case AUTHORIZED_RESULT::NONE:
					
					$_SESSION[SES_KEY::REDIRECT] = $this->redirect;	
					
					// If headers are not sent, redirect to login page. Otherwise we'll just have
					// to settle for an inline message.
					if(headers_sent())
					{
						echo $this->post_header();
					}
					else
					{	
						header('Location: '.$this->config->get_authenticate_url());
					}				
					
					// Exit the script here. This stops bots that ignore headers and prevents 
					// users from proceeding even if headers had already been sent.
					exit;
					break;			
			}
		}
		
		// Output content for user if headers already sent at time of access denial.
		private function post_header()
		{
			$markup = NULL;
			
			ob_start();
			?>
			
			<!DOCtype html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<meta http-equiv="refresh" content="10;URL=<?php echo $this->config->get_authenticate_url(); ?>"> 
					<title>UK - Environmental Health And Safety Login</title>        
				</head>
				
				<body>        
					<?php echo $_SESSION[SES_KEY::DIALOG]; ?>
					
					Click <a href="<?php echo $this->config->get_authenticate_url(); ?>">here</a> to log in as an authorized user.
				</body>
			</html>
							
			<?php
					
			// Collect contents from cache and then clean it.
			$markup = ob_get_contents();
			ob_end_clean();
			
			return $markup;
		}
	}

?>
