<?php

namespace dc\session;

require_once('Config.php');

class Data
{
	private		
		$session_id 	= NULL,
		$session_data	= NULL,
		$expire			= NULL,
		$source			= NULL,
		$ip				= NULL;
		
	// Accessors
	public function get_session_data()
	{
		return $this->session_data;
	}	
}

?>