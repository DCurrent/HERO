<?php 
	
	require(__DIR__.'/source/main.php');
	require(__DIR__.'/source/common_functions/common_security.php');
	
	const LOCAL_STORED_PROC_NAME 	= 'stf_observation_source_read'; 	// Used to call stored procedures for the main record set of this script.
	const LOCAL_BASE_TITLE 			= 'Observation Source';	// Title display, button labels, instruction inserts, etc.
	$primary_data_class				= '\data\ObservationSource';
	
	// common_list
	// Caskey, Damon V.
	// 2017-02-22
	//
	// Switch to list mode for a record. Verifies the list
	// mode file exists first.
	function action_list($_layout = NULL)
	{				
		// Final result, and the target forwarding destination.
		$result 	= '#';
	
		// First thing we need is the self path.				
		$file = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);
		
		// List files are the name of a single record file
		// with _list added on, so all we need to do is
		// remove the file suffix, and add '_list.php' to
		// get the list file's name. This is also all we
		// need for forwarding purposes.	
		$target_name	= basename($file, '_read.php').'_list.php';		
		
		// To verify the list file exists, we have to target the
		// file system path. We can combine the document root
		// and self's directory to get it.
		$root			= filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_URL);
		$directory 		= dirname($file);
		//$target_file	= $root.$directory.'/'.$target_name;		
		$target_file	= $root.$directory.'/';
		
		// Does the list file exisit? If so we can
		// redirect to it. Otherwise, do nothing.
		if(file_exists($target_file))
		{	
			// Set target url.					
			$result = $target_name;			
		
			// Direct to listing.				
			header('Location: '.$result);
		}
		
		// Return final result. 
		return $result;
	}

	// Save this record.
	function action_save($yukon_database)
	{		
		// Set up account info.
		$access_obj = new \dc\stoeckl\status();
				
		// Initialize main data class and populate it from
		// post variables.
		$_main_data = new \data\ObservationSource();						
		$_main_data->populate_from_request();
			
		// Call update stored procedure.
		$yukon_database->set_sql('{call stf_observation_source_update(@param_id			= ?,
												@param_log_update_by	= ?, 
												@param_log_update_ip 	= ?,										 
												@param_label 			= ?,
												@param_details 			= ?,
												@param_observation		= ?,
												@param_solution			= ?,
												@param_status			= ?)}');
												
		$params = array(array('<root><row id="'.$_main_data->get_id().'"/></root>', 		SQLSRV_PARAM_IN),
					array($access_obj->get_id(), 				SQLSRV_PARAM_IN),
					array($access_obj->get_ip(), 			SQLSRV_PARAM_IN),
					array($_main_data->get_label(), 		SQLSRV_PARAM_IN),						
					array($_main_data->get_details(),		SQLSRV_PARAM_IN),
					array($_main_data->get_observation(),	SQLSRV_PARAM_IN),
					array($_main_data->get_solution(),		SQLSRV_PARAM_IN),
					array($_main_data->get_status(),		SQLSRV_PARAM_IN));
		
		//var_dump($params);
		//exit;
		
		$yukon_database->set_params($params);			
		$yukon_database->query();
		
		// Repopulate main data object with results from merge query.
		// We can use common data here because all we need
		// is the ID for redirection.
		$yukon_database->get_line_params()->set_class_name('\data\Common');
		$_main_data = $yukon_database->get_line_object();
		
		// Now that save operation has completed, reload page using ID from
		// database. This ensures the ID is always up to date, even with a new
		// or copied record.
		header('Location: '.$_SERVER['PHP_SELF'].'?id='.$_main_data->get_id()); 
	}
	
	// Verify user access.
	common_security();
		
	// Start page cache.
	$page_obj = new \dc\cache\PageCache();
	
	// Main navigaiton.
	$obj_navigation_main = new class_navigation();	
	
	// Record navigation - also gets user record action requests.
	$obj_navigation_rec = new \dc\recordnav\RecordNav();
	
	// Apply user action request (if any). Depending on the
	// action, the page may be reloaded with the same or
	// another ID.
	switch($obj_navigation_rec->get_action())
	{		
		default:		
		case \dc\recordnav\COMMANDS::NEW_BLANK:
			break;
			
		case \dc\recordnav\COMMANDS::LISTING:
							
			action_list($_layout);
			break;
			
		case \dc\recordnav\COMMANDS::DELETE:						
			
			action_delete($_layout, $yukon_database);	
			break;				
					
		case \dc\recordnav\COMMANDS::SAVE:
			
			action_save($_layout, $yukon_database);			
			break;			
	}
	
	// Last thing to do before moving on to main html is to get data to populate objects that
	// will then be used to generate forms and subforms. This may have already been done, 
	// such as when making copies of a record, but normally only a only blank object 
	// will exist at this point. We run a basic select query from our current ID and 
	// if a row is found overwrite whatever is in the main data object. If needed, we
	// repeat the process for any sub queries and forms.
	//
	// If there is no row at all found, nothing will be done - this is intended behavior because
	// there could be several reasons why no record is found here and we don't want to have 
	// overly complex or repetitive logic, but that does mean we have to make sure there
	// has been an object established at some point above.
	
	// Initialize database query object.
	$query 	= new \dc\yukon\Database($yukon_connection);
	
	// Initialize a blank main data object.
	$_main_data = new $primary_data_class();	
		
	// Populate from request so that we have an 
	// ID and KEY ID (if nessesary) to work with.
	$_main_data->populate_from_request();
	
	// Set up primary query with parameters and arguments.
	$yukon_database->set_sql('{call '.LOCAL_STORED_PROC_NAME.'(@param_filter_id = ?,
									@param_filter_id_key = ?)}');
	$params = array(array($_main_data->get_id(), 		SQLSRV_PARAM_IN),
					array($_main_data->get_id_key(), 	SQLSRV_PARAM_IN));

	// Apply arguments and execute query.
	$yukon_database->set_params($params);
	$yukon_database->query_run();
	
	// Get navigation record set and populate navigation object.		
	$yukon_database->get_line_params()->set_class_name('\dc\recordnav\RecordNav');	
	if($yukon_database->get_row_exists() === TRUE) $obj_navigation_rec = $yukon_database->get_line_object();	
	
	// Get primary data record set.	
	$yukon_database->get_next_result();
	
	$yukon_database->get_line_params()->set_class_name($primary_data_class);	
	if($yukon_database->get_row_exists() === TRUE) $_main_data = $yukon_database->get_line_object();	
	
	// Sub table generation
	// Source lists		
?>


<!DOCtype html>
<html lang="en">
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <title><?php echo APPLICATION_SETTINGS::NAME. ', '.LOCAL_BASE_TITLE; ?></title>        
        
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="source/css/style.css" />
        <link rel="stylesheet" href="source/css/print.css" media="print" />
        
        <style>
						
			.incident {
				font-size:larger;			
			}
			
			ul.checkbox  { 
				
			 	-webkit-column-count: 3;  				
				-moz-column-count: auto;				
			  column-count: 3;			 
			  margin: 10; 
			  padding: 10; 
			  margin-left: 20px; 
			  list-style: none;			  
			} 
			
			ul.checkbox li input { 
			  margin-right: 30px; 
			  cursor:pointer;
			  padding: 10;
			} 
			
			ul.checkbox li { 
			  border: 1px transparent solid; 
			  display:inline-block;
			  width:12em;			  
			} 
			
			ul.checkbox li label { 
			  margin-right: 10px;
			  cursor:pointer;			  
			} 
			
		</style>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>     
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>        
        
        <!-- WYSIWYG Text boxes -->
		<script type="text/javascript" src="source/javascript/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="source/javascript/tinymce/settings.js"></script>
    </head>
    
    <body>    
        <div id="container" class="container">            
            <?php echo $obj_navigation_main->generate_markup_nav(); ?>                                                                                
            <div class="page-header">           
                <h1><?php echo LOCAL_BASE_TITLE; ?></h1>
                <p class="lead">This screen allows for adding and editing <?php echo LOCAL_BASE_TITLE; ?> records.</p>
                <?php require(__DIR__.'/source/common_includes/revision_alert.php'); ?>
            </div>
            
            <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">           
           		<?php echo $obj_navigation_rec->generate_button_list(); ?>
                <hr />
                
                <?php require(__DIR__.'/source/common_includes/details_field.php'); ?>
                
             	<div class="form-group">       
                    <label class="control-label col-sm-2" for="revision">Revision</label>
                    <div class="col-sm-10">
                        <p class="form-control-static"> 
                        <?php if(is_object($_main_data->get_create_time()))
								{
								?>
                                <a id="revision" href = "common_version_list.php?id=<?php echo $_main_data->get_id();  ?>"
                                                            data-toggle	= ""
                                                            title		= "View log for this record."
                                                             target		= "_new" 
                            	><?php  echo date(APPLICATION_SETTINGS::TIME_FORMAT, $_main_data->get_create_time()->getTimestamp()); ?></a>
                        		<?php
								}
								else
								{
								?>
                                	<span class="alert-success">New Record. Fill out form and save to create first revision.</span>
                                <?php
								}
								?>
                                
                    	</p>
                    </div>
                </div>
                                
                <div class="form-group">       
                    <label class="control-label col-sm-2" for="label">Label</label>
                    <div class="col-sm-10">
                        <input 
                            type	="text" 
                            class	="form-control"  
                            name	="label" 
                            id		="label" 
                            placeholder="Observation label." 
                            value="<?php echo trim($_main_data->get_label()); ?>">
                    </div>
                </div> 
                
                <div class="form-group">       
                    <label class="control-label col-sm-2" for="label">Status</label>
                    <div class="col-sm-10">
                      	<label class="radio-inline"><input type="radio" name="status" value="0" <?php if(!$_main_data->get_status()) { echo 'checked'; }?>>Inactive</label>
                       	<label class="radio-inline"><input type="radio" name="status" value="1" <?php if($_main_data->get_status()==1) { echo 'checked'; }?>>Active</label>   
                    </div>
                </div>
                
                <div class="form-group">       
                    <label class="control-label col-sm-2" for="observation">Observation</label>
                    <div class="col-sm-10">
                    	<textarea class="form-control wysiwyg" rows="2" name="observation" id="observation"><?php echo $_main_data->get_observation(); ?></textarea>                          
                    </div>
                </div>  
                
                <div class="form-group">       
                    <label class="control-label col-sm-2" for="solution">Solution</label>
                    <div class="col-sm-10">
                    	<textarea class="form-control wysiwyg" rows="2" name="solution" id="solution"><?php echo $_main_data->get_solution(); ?></textarea>                          
                    </div>
                </div>    
               
                 
                <hr />
                <div class="form-group">
                	<div class="col-sm-12">
                		<?php echo $obj_navigation_rec->get_markup_cmd_save_block(); ?>
                	</div>
                </div>               
            </form>
            
            <?php echo $obj_navigation_main->generate_markup_footer(); ?>
        </div><!--container-->        
		<script src="source/javascript/verify_save.js"></script>
		<script>
            //Google Analytics Here// 
        
            $(document).ready(function(){
            });
        </script>
	</body>
</html>

<?php
	// Collect and output page markup.
	echo $page_obj->markup_and_flush();
?>