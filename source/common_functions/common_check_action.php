<?php

function common_check_action($action = NULL, $_layout = NULL)
{
	switch($action)
	{		
		default:		
		case \dc\recordnav\COMMANDS::NEW_BLANK:
			break;
			
		case \dc\recordnav\COMMANDS::LISTING:
			
			
			if(!function_exists('common_list'))
			{
				require_once('common_list.php');
			}
			
			common_list();
			break;
			
		case \dc\recordnav\COMMANDS::DELETE:						
			
			if(!function_exists('common_delete'))
			{
				require_once('common_delete.php');
			}
			
			common_delete();	
			break;				
					
		case \dc\recordnav\COMMANDS::SAVE:
			
			action_save($yukon_database);			
			break;			
	}
}
?>
