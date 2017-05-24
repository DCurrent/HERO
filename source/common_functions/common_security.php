<?php
	
	// Check current user against security settings
	// and redirect to login prompt if user does
	// not have nessesary access.
	function common_security()
	{
		// Open record navigation object so we can
		// get variables for redirect URL.
		$obj_navigation_rec = new \dc\recordnav\RecordNav();	
		
		// Initialize redirect url object and 
		// populate variables.
		$url_query	= new \dc\url\URLFix;
		$url_query->set_data('action', $obj_navigation_rec->get_action());
		$url_query->set_data('id', $obj_navigation_rec->get_id());
		$url_query->set_data('id_key', $obj_navigation_rec->get_id_key());
			
		// User access.
		$access_obj = new \dc\access\status();
		$access_obj->get_config()->set_authenticate_url(APPLICATION_SETTINGS::DIRECTORY_PRIME);
		$access_obj->set_redirect($url_query->return_url());
		
		$access_obj->verify();	
		$access_obj->action();
	}

?>