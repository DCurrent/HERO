<?php 
	
	require(__DIR__.'/source/main.php');
	require(__DIR__.'/source/common_functions/common_security.php');
	
	const LOCAL_STORED_PROC_NAME 	= 'stf_observation_target_read'; 	// Used to call stored procedures for the main record set of this script.
	const LOCAL_BASE_TITLE 			= 'Observation';	// Title display, button labels, instruction inserts, etc.
	$primary_data_class				= '\data\Area';
	
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
	function action_save()
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
		$query->set_sql('{call stf_observation_target_update(@param_id			= ?,
												@param_log_update_by	= ?, 
												@param_log_update_ip 	= ?,										 
												@param_label 			= ?,
												@param_details 			= ?,
												@param_building_code	= ?,
												@param_room_code		= ?)}');
												
		$params = array(array('<root><row id="'.$_main_data->get_id().'"/></root>', 		SQLSRV_PARAM_IN),
					array($access_obj->get_id(), 				SQLSRV_PARAM_IN),
					array($access_obj->get_ip(), 			SQLSRV_PARAM_IN),
					array($_main_data->get_label(), 		SQLSRV_PARAM_IN),						
					array($_main_data->get_details(),		SQLSRV_PARAM_IN),
					array($_main_data->get_building_code(),	SQLSRV_PARAM_IN),
					array($_main_data->get_room_code(),		SQLSRV_PARAM_IN));
		
		//var_dump($params);
		//exit;
		
		$query->set_params($params);			
		$query->query();
		
		// Repopulate main data object with results from merge query.
		// We can use common data here because all we need
		// is the ID for redirection.
		$query->get_line_params()->set_class_name('\data\Common');
		$_main_data = $query->get_line_object();
		
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
			
			action_delete($_layout);	
			break;				
					
		case \dc\recordnav\COMMANDS::SAVE:
			
			action_save($_layout);			
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
	$query 	= new \dc\yukon\Database();
	
	// Initialize a blank main data object.
	$_main_data = new $primary_data_class();	
		
	// Populate from request so that we have an 
	// ID and KEY ID (if nessesary) to work with.
	$_main_data->populate_from_request();
	
	// Set up primary query with parameters and arguments.
	$query->set_sql('{call '.LOCAL_STORED_PROC_NAME.'(@param_filter_id = ?,
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
	
	$query->get_line_params()->set_class_name($primary_data_class);	
	if($query->get_row_exists() === TRUE) $_main_data = $query->get_line_object();	
	
	// Sub - Party.
	$query->get_next_result();
	
	$query->get_line_params()->set_class_name('\data\ObservationSource');
	
	$_list_observation_source = new SplDoublyLinkedList();
	if($query->get_row_exists()) $_list_observation_source = $query->get_line_object_list();
	
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
                <p class="lead">This screen allows you to add or edit an observation for any slip, trip, or fall hazards. Follow the instructions below to enter an observation set.</p>
                <?php require(__DIR__.'/source/common_includes/revision_alert.php'); ?>
            </div>
            
            <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">           
           		<?php $obj_navigation_rec->generate_button_list(); ?>
                <?php echo $obj_navigation_rec->get_markup_cmd_save_block(); ?>
                <hr />
                
                <?php require(__DIR__.'/source/common_includes/details_field.php'); ?>
                
                <!--
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
                </div>-->
                
                                
                <div class="form-group">  
                    <label class="control-label col-sm-2" for="label">Label</label>                    
                    <div class="col-sm-10">
                       	<span class=".small">A label is just for your own use in case you'd like to have an easy reference to this observation set. It is not required.</span>
                       	<br />
                       	&nbsp;
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
					<label class="control-label col-sm-2" for="building_code">Building</label>					
					<div class="col-sm-10">
						<span class=".small">A building is required. If the observation is outside, then select the nearest building instead. Buildings are arranged in alphabetical order. If you know the building's number, you can type it while the list is open to more quickly locate the item you are looking for.</span>
						<br />
						&nbsp;
						<select name="building_code" 
							id="building_code" 
							data-current="<?php echo $_main_data->get_building_code(); ?>" 
							data-source-url="../../libraries/inserts/facility.php" 
							data-extra-options='<option value="">Select Facility</option>'
							data-grouped="1"
							class="room_search form-control">
								<!--This option is for valid HTML5; it is overwritten on load.--> 
								<option value="0">Select Building</option>                                    
								<!--Options will be populated on load via jquery.-->                                 
						</select>
					</div>
				</div> 
                    
				<div class="form-group">
					<label class="control-label col-sm-2" for="room_code">Area</label>
					<div class="col-sm-10">
						<span class=".small">The area is your room, laboratory, or whatever space you make an observation in. All areas in a UK Facility are given their own room identity - even places like closets, hallways, and common spaces. The rooms here are arranged by floor, and then room number. Choices are also included for areas outside of a building.</span>
						<br />
						&nbsp;
						<select name="room_code" 
							id="room_code" 
							data-facility="<?php echo $_main_data->get_building_code(); ?>"
							data-current="<?php echo $_main_data->get_room_code(); ?>" 

							data-source-url="../../libraries/inserts/room.php" 
							data-grouped="1" 
							data-extra-options='<option value="">Select Room/Area/Lab</option> <optgroup label="Outside"><option value=-1>Walkway</option><option value=-2>Loading Area</option></optgroup>' 
							class="room_code_search disable form-control" 
							disabled>                                        
								<!--Options will be populated/replaced on load via jquery.-->
								<option value="0">Select Room/Area/Lab</option>                                  							
						</select> 
					</div>                                   
				</div>    
               
               	
               	<div class="form-group" id="fg_observations">       
				  <div class="col-sm-2">
				  </div>                
				  <fieldset class="col-sm-10">
						<legend>Observations</legend>
						
						<span class=".small">These are the observations we would like you to consider. Read each item carefully, and then choose the appropriate response. When you are finished, press the Save key to record your answers. You will then be given suggestions for taking any necessary corrective actions.</span>
						<br />
						&nbsp;
						<table class="table table-striped table-hover table-condensed" id="tbl_sub_visit"> 
							<thead>								
							</thead>
							<tfoot>
							</tfoot>
							<tbody id="tbody_observations" class="observation">                        
								<?php                              
								if(is_object($_list_observation_source) === TRUE)
								{    
									// Start a counter.
									$observation_count = 0;
										
									// Generate table row for each item in list.
									for($_list_observation_source->rewind(); $_list_observation_source->valid(); $_list_observation_source->next())
									{	
										// Increment counter.
										$observation_count++;
											
										$_observation_source_current = $_list_observation_source->current();

										// Blank IDs will cause a database error, so make sure there is a
										// usable one here.
										if(!$_observation_source_current->get_id_key()) $_observation_source_current->set_id(\dc\yukon\DEFAULTS::NEW_ID);

									?>
										<tr>
											<th><?php echo $observation_count; ?>:</th>
											<td><?php echo $_observation_source_current->get_observation(); ?>
												<br />
												<div class="form-group">									
													<div class="col-sm-10">
														<label class="radio-inline"><input type="radio" 
															name	= "status_<?php echo $_observation_source_current->get_id(); ?>[]"
															id		= "status_<?php echo $_observation_source_current->get_id(); ?>"
															value	= "1"
															<?php if($_observation_source_current->get_result()==1){ echo ' checked'; } ?>>Yes</label>
														
														<label class="radio-inline"><input type	= "radio" 
															name	= "status_<?php echo $_observation_source_current->get_id(); ?>[]" 
															id		= "status_<?php echo $_observation_source_current->get_id(); ?>"
															value	= "0"
															<?php if(!$_observation_source_current->get_result()){ echo ' checked'; } ?>>No</label>   
													</div>
												</div>
												<!-- Result table item field is populated with ID from source table
													 is so we know which observation the result is refering to. -->
												<input type	= "hidden" 
													name	= "item[]" 
													id		= "item_<?php echo $_observation_source_current->get_id(); ?>" 
													value	= "<?php echo $_observation_source_current->get_id(); ?>"> 
											</td>
										</tr>                                    
								<?php
									}
								}
								?>                        
							</tbody>                        
						</table> 
					</fieldset>
				</div><!--/fg_observations-->
               
                 
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
		<script src="../../libraries/javascript/options_update.js"></script>
		<script>
            // Google Analytics Here// 
        
            $(document).ready(function(){
            });
       
			// Building & area entry
			$(document).ready(function(event) {		

						// Populate building seelct list.
						options_update(event, null, '#building_code');
						
						// If the room and building fields are 
						// populated, we need to populate the 
						// room select list too so current room 
						// selection is visible.
						<?php
						if($_main_data->get_building_code() && $_main_data->get_room_code())
						{
						?>
							 options_update(event, null, '#room_code');
						<?php
						}
						?>

					});

			// Room search and add.
			$('.room_search').change(function(event)
			{
				options_update(event, null, '#room_code');	
			});
			
		</script>
	</body>
</html>

<?php
	// Collect and output page markup.
	echo $page_obj->markup_and_flush();
?>