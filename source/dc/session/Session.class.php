<?php

namespace dc\session;

require_once('config.php');

// Override PHP's default session handling to store data in an MSSQL table. 
class Session implements \SessionHandlerInterface
{    
	
	private	
		$db_conn_m	= NULL,	// Database conneciton object.
		$iDatabase_m	= NULL,	// Database query object.
		$life		= NULL;	// Maximum lifetime of session.

	function __construct()
	{
		// echo '__construct';
								
		// Set class vars.
		$this->life = DEFAULTS::LIFE;			
		
		$this->iDatabase_m	= new \dc\yukon\Database();
	}
	
	function __destruct()
	{
		// echo '__destruct';		
	}
    
	// Called by PHP to open session. Construct does all the work
	// we'd normally put here, so leave it blank.
   	public function open($savePath, $sessionName)
    {	
		// echo 'open';
		
		
		
		// $savePath: 		Path to locate session file. Unused.
		// $sessionName:	Name of session file. Unused.

		// Return TRUE.
        return TRUE;
    }
	
	// Called by PHP to close session.
    public function close()
    {	
		// echo 'close';
		
		
		
		// Return TRUE.
        return TRUE;
    }

	// Locate and read session data from database.
    public function read($id)
    {		
		// echo 'read';        

		// Dereference query object.
		$iDatabase = $this->iDatabase_m;
		
		// String - call stored procedure.
		$iDatabase->set_sql('{call stf_session_get(@id = ?)}');				

		// Prepare parameter array.
		$params = array(array(&$id, SQLSRV_PARAM_IN));
		
		// Bind parameters and execute query.
		$iDatabase->set_params($params);				
		$iDatabase->query();
		
		
		if($iDatabase->get_row_exists())
		{
			// Set class and acquire object.
			$iDatabase->get_line_params()->set_class_name(__NAMESPACE__.'\Data');
			$result = $iDatabase->get_line_object();	
		}
		else
		{
			$result = new Data();
		}
		
		// Return session data.
		return $result->get_session_data();		
    }

	// Update or insert session data. Note that only ID and Session Data are 
	// required. Other data is to aid in debugging.
    public function write($id, $data)
    {
		// echo 'write';
						
		$source	= $_SERVER['PHP_SELF'];		// Current file.
		$ip		= $_SERVER['REMOTE_ADDR'];	// Client IP address.					
		
		// Time value so we know when the session has expired. While we have the option
		// to set a time here, for consistency we'll normally just send NULL, and 
		// and let the stored procedure add in the database engine's own current time.
		$time	= NULL; //date(DATE_ATOM);
		
		// Ensure IP string is <= 15. Anything over is a MAC or unexpected (and useless) value. */
		$ip = substr($ip, 0, 15);
		
		// Dereference query object.
		$iDatabase = $this->iDatabase_m;
		
		// SQL string - call stored procedure.
		$iDatabase->set_sql('{call stf_session_set(@id 			= ?,
											@data 			= ?,											
											@source 		= ?,
											@ip 			= ?)}');				

		// Prepare parameter array.
		$params = array(array($id, SQLSRV_PARAM_IN),
						array($data, SQLSRV_PARAM_IN),
						array($source, SQLSRV_PARAM_IN),
						array($ip, SQLSRV_PARAM_IN));
						
		// Bind parameters and execute query.
		$iDatabase->set_params($params);				
		$iDatabase->query();		
					
		// Return TRUE. 
		return TRUE;
    }

	// Delete current session.
    public function destroy($id)
    {	
		// echo 'Destroy';

		// Dereference query object.
		$iDatabase = $this->iDatabase_m;
		
		// SQL string - call stored procedure.
		$iDatabase->set_sql('{call stf_session_destroy(@id = ?)}');				

		// Prepare parameter array.
		$params = array(array(&$id, SQLSRV_PARAM_IN));
		
		// Bind parameters and execute query.
		$iDatabase->set_params($params);				
		$iDatabase->query();		
		
		// Return TRUE.
		return TRUE;
    }

	// Delete expired session data.
		
	//	$life_max: Maximum lifetime of a session, in seconds. This is
	//	passed from the php.ini session.gc_maxlifetime setting.
    public function gc($life_max)
    {
		// echo 'gc';
		
		// If local setting isn't NULL, use it to override the default
		// lifetime value.
		if(DEFAULTS::LIFE != NULL)
		{
			$life_max = DEFAULTS::LIFE;
		}
		
		// Dereference query object.
		$iDatabase = $this->iDatabase_m;
		
		// SQL string - call stored procedure.
		$iDatabase->set_sql('{call stf_session_clean(@life_max = ?)}');				

		// Prepare parameter array.
		$params = array(array(&$life_max, SQLSRV_PARAM_IN));
		
		// Bind parameters and execute query.
		$iDatabase->set_params($params);				
		$iDatabase->query();		
		
		// Return TRUE.
		return TRUE;
    }
}

