<?php 
	
	namespace dc\access;

	abstract class DEFAULTS
	{	
		const ADMINISTRATOR		= 'dvcask2';					// Administrator.
		const LDAP_HOST_BIND	= 'LDAP://gc.ad.uky.edu';		// LDAP host for binding.
		const LDAP_HOST_DIR		= 'ukldap.uky.edu';				// LDAP host for directory
		const LDAP_BASE_DN		= 'o=uky';						// LDAP Base Domain Name.
		const AUTHENTICATE_URL	= '/z_authenticate_dev.php';	// Authenticate page URL.
		const DOMAIN_PREFIX		= ', ad/, ad\, mc\, mc/';
		const DIAGNOSTIC		= FALSE;						// Record user info to outside database for diagnostics.
		const DIAGNOSTIC_MAIL	= TRUE;							// Record user info to email for diagnostics.
		const USE_LOCAL			= TRUE;							// Attempt to log in user with local account.
	}
	
	abstract class SES_KEY
	{
		const REDIRECT		= 'access_redirect';				// URL to send user on successfull login.
		const DIALOG		= 'access_dialog';					// Session ID of authorization dialog.
		
		const ID			= 'account_id';						// Session ID of account id assigned by local account database.
		const ACCOUNT		= 'access_cn';						// Session ID of account name currently in session.
		const NAME_F		= 'access_name_f';					// Session ID of first name belonging to account currently in session.
		const NAME_M		= 'access_name_m';		
		const NAME_L		= 'access_name_l';
		const EMAIL			= 'access_email';
		const ACCOUNT_ID	= 'account_natural_id';				// ID number given to account (if any. Example: UK ID).
	}
	
	abstract class ACTION
	{
		const LOGIN			= 1;	// Account and password fields left empty by user.
		const LOGOFF		= 2;	// Login success through local account.		
	}
	
	abstract class LOGIN_RESULT
	{
		const NO_INPUT		= 0;	// Account and password fields left empty by user.
		const LOCAL			= 1;	// Login success through local account.
		const LDAP			= 2;	// Login success through LDAP.
		const NO_BIND		= 3;	// LDAP bind failure (probably bad password).
		const NO_PRINCIPAL	= 4;	//
		const NO_ACCOUNT	= 5;					
		const NO_RESULT		= 6;	// No result at all from LDAP query.
		const NO_LDAP		= 7;	// Could not connect to LDAP.
		const LOGOFF		= 8;	// User requested log off.
	}
	
	abstract class AUTHORIZED_RESULT
	{
		const NONE	= 0;
		const NO	= 1;
		const YES	= 2;
	}
?>