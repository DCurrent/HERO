<?php 
	
	require(__DIR__.'/source/main.php');
	require(__DIR__.'/source/common_functions/common_security.php');	
	
	$_page_config = new \dc\application\CommonEntryConfig();
	
	$_layout = $_page_config->create_config_object();
	
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
		
		// List giles are the name of a single record file
		// with _list added on, so all we need to do is
		// remove the file suffix, and add '_list.php' to
		// get the list file's name. This is also all we
		// need for forwarding purposes.	
		$target_name	= basename($file, '.php').'_list.php';		
		
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
			header('Location: '.$result.'?id_form='.$_layout->get_id());
		}
		
		// Return final result. 
		return $result;
	}
			
	// Save this record.
	function action_save($_layout = NULL)
	{		
		// Initialize database query object.
		$query 	= new \dc\yukon\Database();
		
		// Set up account info.
		$access_obj = new \dc\access\status();
				
		// Initialize main data class and populate it from
		// post variables.
		$_main_data = new \data\Common();						
		$_main_data->populate_from_request();
			
		// Call update stored procedure.
		$query->set_sql('{call '.$_layout->get_main_sql_name().'_update(@id			= ?,
												@log_update_by	= ?, 
												@log_update_ip 	= ?,										 
												@label 			= ?,
												@details 		= ?)}');
												
		$params = array(array('<root><row id="'.$_main_data->get_id().'"/></root>', 		SQLSRV_PARAM_IN),
					array($access_obj->get_id(), 				SQLSRV_PARAM_IN),
					array($access_obj->get_ip(), 			SQLSRV_PARAM_IN),
					array($_main_data->get_label(), 		SQLSRV_PARAM_IN),						
					array($_main_data->get_details(),		SQLSRV_PARAM_IN));
		
		$query->set_params($params);			
		$query->query();
		
		// Repopulate main data object with results from merge query.
		// We can use common data here because all we need
		// is the ID for redirection.
		$query->get_line_params()->set_class_name($_layout->get_main_object_name());
		$_main_data = $query->get_line_object();
		
		// Now that save operation has completed, reload page using ID from
		// database. This ensures the ID is always up to date, even with a new
		// or copied record.
		header('Location: '.$_SERVER['PHP_SELF'].'?id_form='.$_layout->get_id().'&id='.$_main_data->get_id());
	}
	
	function action_delete($_layout = NULL)
	{
		// Initialize database query object.
		$query 	= new \dc\yukon\Database();
		
		// Set up account info.
		$access_obj = new \dc\access\status();
				
		// Initialize main data class and populate it from
		// post variables.
		$_main_data = new \data\Area();						
		$_main_data->populate_from_request();
			
		// Call update stored procedure.
		$query->set_sql('{call master_delete(@param_id			= ?,
												@param_update_by	= ?, 
												@param_update_ip 	= ?)}');
												
		$params = array(array($_main_data->get_id(), 		SQLSRV_PARAM_IN),
					array($access_obj->get_id(), 				SQLSRV_PARAM_IN),
					array($access_obj->get_ip(), 			SQLSRV_PARAM_IN));
		
		$query->set_params($params);			
		$query->query();
		
		// Repopulate main data object with results from merge query.
		// We can use common data here because all we need
		// is the ID for redirection.
		$query->get_line_params()->set_class_name($_layout->get_main_object_name());
		$_main_data = $query->get_line_object();
		
		// Now that save operation has completed, reload page using ID from
		// database. This ensures the ID is always up to date, even with a new
		// or copied record.
		header('Location: '.$_SERVER['PHP_SELF'].'?id_form='.$_layout->get_id());
	}
			
	
	///////////////
	
	
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
			
			action_delete($_layout);	
			break;				
					
		case \dc\recordnav\COMMANDS::SAVE:
			
			action_save($_layout);			
			break;			
	}
	
	// Initialize database query object.
	$query 	= new \dc\yukon\Database();
	
	// Class name has to be populated into local var to
	// be instantiated.
	$main_data_class_name = $_layout->get_main_object_name();
	
	// Initialize a blank main data object.
	$_main_data = new $main_data_class_name();	
		
	// Populate from request so that we have an 
	// ID and KEY ID (if nessesary) to work with.
	$_main_data->populate_from_request();
	
	// Set up primary query with parameters and arguments.
	$query->set_sql('{call '.$_layout->get_main_sql_name().'(@param_filter_id = ?,
									@param_filter_id_key = ?)}');
	$params = array(array($_main_data->get_id(), 		SQLSRV_PARAM_IN),
					array($_main_data->get_id_key(), 	SQLSRV_PARAM_IN));

	// Apply arguments and execute query.
	$query->set_params($params);
	$query->query();
	
	// Get navigation record set and populate navigation object.		
	$query->get_line_params()->set_class_name('\dc\recordnav\RecordNav');	
	if($query->get_row_exists() === TRUE) $obj_navigation_rec = $query->get_line_object();	
	
	// Get primary data record set.	
	$query->get_next_result();
	
	$query->get_line_params()->set_class_name($_layout->get_main_object_name());	
	if($query->get_row_exists() === TRUE) $_main_data = $query->get_line_object();		
	
?>

<!DOCtype html>
<html lang="en">
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <title><?php echo APPLICATION_SETTINGS::NAME; 
		
			// Add page title, if any.
			if($_layout->get_title())
			{
				echo ', '.$_layout->get_title();
            }?></title>        
        
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="source/css/style.css" />
        <link rel="stylesheet" href="source/css/print.css" media="print" />
        
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
                <h1><?php
					// Add page title, if any.
					if($_layout->get_title())
					{
						echo $_layout->get_title();
					}?></h1>
                <?php
					// Add page description, if any.
					if($_layout->get_description())
					{
						echo $_layout->get_description();
					}?>
                                
				<?php
					// If this isn't the active version, better alert user.
					if(!$_main_data->get_active() && $_main_data->get_id_key())
					{
					?>
						<div class="alert alert-warning">
							<strong>Notice:</strong> You are currently viewing an inactive revision of this record. Saving will make this the active revision. To return to the currently active revision without saving, click <a href="<?php echo $_SERVER['PHP_SELF'].'?id_form='.$_layout->get_id().'&amp;id='.$_main_data->get_id(); ?>">here</a>.
						</div>
					<?php
					}
				?>
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
                                <a id="revision" href = "common_version_list.php?id_form=<?php echo $_layout->get_id(); ?>&amp;id=<?php echo $_main_data->get_id();  ?>"
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
                            placeholder="Label or name for item." 
                            value="<?php echo trim($_main_data->get_label()); ?>">
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