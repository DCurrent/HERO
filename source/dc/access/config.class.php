<?php

	namespace dc\access;

	require('config.php');

	interface iconfig
	{
		// Accessors	
		function get_administrator();		
		function get_ldap_host_bind();		
		function get_ldap_host_dir();		
		function get_ldap_base_dn();		
		function get_dn_prefix();		
		function get_authenticate_url();		
		function get_diagnostic();		
		function get_diagnostic_mail();		
		function get_use_local();
		
		// Mutators
		function set_administrator($value);	
		function set_ldap_host_bind($value);	
		function set_ldap_host_dir($value);			
		function set_ldap_base_dn($value);		
		function set_authenticate_url($value);		
		function set_diagnostic($value);		
		function set_diagnostic_mail($value);		
		function set_use_local($value);		
	}

	class config implements iconfig
	{
		private
			$administrator		= NULL,
			$ldap_host_bind		= NULL,
			$ldap_host_dir		= NULL,
			$ldap_base_dn		= NULL,
			$dn_prefix			= NULL,
			$authenticate_url	= NULL,
			$diagnostic			= NULL,
			$diagnostic_mail	= NULL,
			$use_local			= NULL;
		
		public function __construct()
		{		
			$this->administrator 	= DEFAULTS::ADMINISTRATOR;
			$this->ldap_host_bind	= DEFAULTS::LDAP_HOST_BIND;
			$this->ldap_host_dir	= DEFAULTS::LDAP_HOST_DIR;
			$this->ldap_base_dn		= DEFAULTS::LDAP_BASE_DN;
			$this->dn_prefix		= DEFAULTS::DOMAIN_PREFIX;
			$this->authenticate_url	= DEFAULTS::AUTHENTICATE_URL;
			$this->diagnostic		= DEFAULTS::DIAGNOSTIC;
			$this->diagnostic_mail	= DEFAULTS::DIAGNOSTIC_MAIL;
			$this->use_local		= DEFAULTS::USE_LOCAL;		
		}
		
		// Accessors	
		public function get_administrator()
		{
			return $this->administrator;
		}
		
		public function get_ldap_host_bind()
		{
			return $this->ldap_host_bind;
		}
		
		public function get_ldap_host_dir()
		{
			return $this->ldap_host_dir;
		}
		
		public function get_ldap_base_dn()
		{
			return $this->ldap_base_dn;
		}
		
		public function get_dn_prefix()
		{
			return $this->dn_prefix;
		}
		
		public function get_authenticate_url()
		{
			return $this->authenticate_url;
		}
		
		public function get_diagnostic()
		{
			return $this->diagnostic;
		}
		
		public function get_diagnostic_mail()
		{
			return $this->diagnostic_mail;
		}
		
		public function get_use_local()
		{
			return $this->use_local;
		}
		
		// Mutators
		public function set_administrator($value)
		{
			$this->administrator = $value;
		}
		
		public function set_ldap_host_bind($value)
		{
			$this->ldap_host_bind = $value;
		}
		
		public function set_ldap_host_dir($value)
		{
			$this->ldap_host_dir = $value;
		}
			
		public function set_ldap_base_dn($value)
		{
			$this->ldap_base_dn = $value;
		}
		
		public function set_authenticate_url($value)
		{
			$this->authenticate_url = $value;
		}
		
		public function set_diagnostic($value)
		{
			$this->diagnostic = $value;
		}
		
		public function set_diagnostic_mail($value)
		{
			$this->diagnostic_mail = $value;
		}
		
		public function set_use_local($value)
		{
			$this->use_local = $value;
		}
	}


?>