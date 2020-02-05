<?php

	abstract class APPLICATION_SETTINGS
	{
		const
			VERSION 		= '0.1.2',
			NAME			= 'Slips, Trips, and Falls; Hazard Recognition',
			DIRECTORY_PRIME	= '/apps/hero',
			TIME_FORMAT		= 'Y-m-d H:i:s',
			PAGE_ROW_MAX	= 25;
		
		const
			SESSION_PREFIX	= 'stf_';
	}

	abstract class DATABASE
	{
		const 
			HOST 		= 'GENSQLAGL\general',	// Database host (server name or address)
			NAME 		= 'ehsinfo',					// Database logical name.
			USER 		= 'EHSInfo_User',				// User name to access database.
			PASSWORD	= 'ehsinfo',					// Password to access database.
			CHARSET		= 'UTF-8';						// Character set.
	}

	abstract class MAILING
	{
		const
			TO		= '',
			CC		= '',
			BCC		= 'dc@caskeys.com',
			SUBJECT = 'STF Alert',
			FROM 	= 'ehs_noreply@uky.edu';
	}
	
	abstract class SESSION_ID
	{
		const
			LAST_BUILDING	= 'id_last_building';	// Last building choosen by user.
	}

?>