<?php 
	
	require(__DIR__.'/source/main.php');
	require(__DIR__.'/source/common_functions/common_security.php');
	require('../../libraries/vendor/mpdf/mpdf.php');	// pdf maker.
	
	const LOCAL_STORED_PROC_NAME 	= 'stf_observation_target_read'; 	// Used to call stored procedures for the main record set of this script.
	const LOCAL_BASE_TITLE 			= 'Observation';					// Title display, button labels, instruction inserts, etc.
	$primary_data_class				= '\data\Area';

		
	
	// Initialize pdf maker class.
	$pdf_gen = new mPDF();
	
	$pdf_gen->SetTitle('EHS Class Certificate');	
	$pdf_gen->SetCreator('Caskey, Damon V.');
	$pdf_gen->AddPage('L'); // Adds a new page in Landscape orientation	
	
	// Verify user access.
	common_security();
		
	// Start page cache.
	$page_obj = new \dc\cache\PageCache();
	
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
<html lang="en">
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <title><?php echo APPLICATION_SETTINGS::NAME. ', '.LOCAL_BASE_TITLE; ?></title>        
        
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        
        
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
    </head>
    
    <body>    
        <div id="container" class="container">                                              
            <div class="page-header">           
                <h1><?php echo LOCAL_BASE_TITLE; ?></h1>
                <p class="lead">This printable observation form is provided for your convenience. Completed observations must be submitted with the Hero Application.</p>
            </div>
            
            <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">           
                <hr />       
                
                <div class="form-group">					
					<label class="control-label col-sm-2" for="building_code">Building <a href="#help_building" data-toggle="collapse" class="glyphicon glyphicon-question-sign"></a></label>					
					
					<div class="col-sm-10">
						
						<div id="help_building" class="collapse text-info">
							A building is required. If the observation is outside, then select the nearest building instead. Buildings are arranged in alphabetical order. If you know the building's number (speed sort), you can type it while the list is open to more quickly locate the item you are looking for. <a href="#help_building" data-toggle="collapse" class="glyphicon glyphicon-remove-sign text-danger"></a>
							<br />
							&nbsp;	
						</div> 
					</div>
				</div> 
                    
				<div class="form-group">
					<label class="control-label col-sm-2" for="room_code">Area <a href="#help_area" data-toggle="collapse" class="glyphicon glyphicon-question-sign"></a></label>
					<div class="col-sm-10">
						
						<div id="help_area" class="collapse text-info">
							The area is your room, laboratory, or whatever space you make an observation in. All areas in a UK Facility are given their own room identity - even places like closets, hallways, and common spaces. The rooms here are arranged by floor, and then room number. Choices are also included for areas outside of a building. <a href="#help_area" data-toggle="collapse" class="glyphicon glyphicon-remove-sign text-danger"></a>	
							<br />
							&nbsp;
						</div>
						
					</div>                                   
				</div>    
               
               	
               	<div class="form-group" id="fg_observations">       
				  	<!--div class="col-sm-2">
				  	</div-->                
					<fieldset class="col-sm-12">
						<legend>Observations</legend>
						
						<div class="col-sm-2"></div>
						<div class="col-sm-10">
							
							
							<table class="table table-striped table-hover table-condensed" id="tbl_sub_visit"> 
								<thead>								
								</thead>
								<tfoot>
								</tfoot>
								<tbody id="tbody_observations" class="observation table-striped">                        
									<?php                              
									if(is_object($_list_observation_source) === TRUE)
									{    
										// Start a counter.
										$observation_count = 0;

										// Generate table row for each item in list.
										for($_list_observation_source->rewind(); $_list_observation_source->valid(); $_list_observation_source->next())
										{											
											$_observation_source_current = $_list_observation_source->current();

											// Blank IDs will cause a database error, so make sure there is a
											// usable one here.
											if(!$_observation_source_current->get_id_key()) $_observation_source_current->set_id(\dc\yukon\DEFAULTS::NEW_ID);

											// Just to shorten the ID references below.
											$_id = $_observation_source_current->get_id();

										?>
											<tr>
												<th><?php echo $observation_count+1; ?>:</th>
												<td><?php echo $_observation_source_current->get_observation(); ?>
													<br />
													<!-- Observation toggles. Current value: <?php echo $_observation_source_current->get_result(); ?>-->
													<div class="form-group">									
														<div class="col-sm-10">
															<label class="radio-inline"><input type="radio" 
																class	= "result_<?php echo $_id; ?>"
																name	= "result_<?php echo $_id; ?>"
																id		= "result_<?php echo $_id; ?>_1"
																value	= "1"
																required
																<?php if($_observation_source_current->get_result()===1){ echo ' checked'; } ?>><span class="glyphicon glyphicon-thumbs-up text-success" style="font-size:large;"></span></label>

															<label class="radio-inline"><input type	= "radio" 
																class	= "result_<?php echo $_id; ?>"
																name	= "result_<?php echo $_id; ?>" 
																id		= "result_<?php echo $_id; ?>_0"
																value	= "0"
																required
																<?php if($_observation_source_current->get_result()===0){ echo ' checked'; } ?>><span class="glyphicon glyphicon-thumbs-down text-danger" style="font-size:large;"></span></label>   
														</div>
													</div>
																		
															<!-- Collapsed by default, with a jquery toggle below
															that will display if the user activly selects 'no'. 
															PHP will insert 'in' value to the 'collpase' class to have
															the item displayed on page load if the checked value
															is already 'no'. -->
															<div class="text-info collapse <?php if($_observation_source_current->get_result()===0) echo 'in' ?> result_solution_<?php echo $_id; ?>">
																	<h4>Suggestions:</h4>																	
																	<?php echo $_observation_source_current->get_solution(); ?>
															</div>
														

														<script>
															// Fire whenever a result check value is modified.
															$('.result_<?php echo $_id; ?>').on('change', function() {

																// If 0 (no) is checked, then display the solution field.
																// Otherwise, collapse it. 
																if($('#result_<?php echo $_id; ?>_0').is(':checked')) {
																  $('.result_solution_<?php echo $_id; ?>').collapse('show');
																} else {
																  $('.result_solution_<?php echo $_id; ?>').collapse('hide');
																}
															  });
														</script>

													<!-- Result table item field is populated with ID from source table
														 is so we know which observation the result is refering to. -->
													<input type	= "hidden" 
														name	= "item[]"
														id		= "item_<?php echo $_observation_source_current->get_id(); ?>" 
														value	= "<?php echo $_observation_source_current->get_id(); ?>">
												</td>
											</tr>                                    
									<?php
											// Increment counter.
											$observation_count++;
										}
									}
									?>                        
								</tbody>                        
							</table> 
						</div>
					</fieldset>
				</div><!--/fg_observations-->
               
               	<div class="form-group">  
                    <label class="control-label col-sm-2" for="details">Additional Observations</label>                    
                    <div class="col-sm-10">
                       	<span class=".small">If you have any other notes or observations you would like to include, feel free to add them here.</span>
                       	<br />
                       	&nbsp;
                        <textarea class="form-control wysiwyg" rows="5" name="details" id="details"><?php echo $_main_data->get_details(); ?></textarea>
                    </div>
                </div> 
               
                
                 
                <hr />      
            </form>
        </div><!--container-->        
		<script src="source/javascript/verify_save.js"></script>
		<script src="../../libraries/javascript/options_update.js"></script>
		<script>
            // Google Analytics Here// 
        
			$('input[type=radio][data-toggle=radio-collapse]').each(function(index, item) {
				  var $item = $(item);
				  var $target = $($item.data('target'));

				  $('input[type=radio][name="' + item.name + '"]').on('change', function() {
					if($item.is(':checked')) {
					  $target.collapse('show');
					} else {
					  $target.collapse('hide');
					}
				  });
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
				
						$('#room_code').attr("data-current", null);

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
	// Collect contents from cache and then clean it.
	$content = $page_obj->markup_from_cache();
	$page_obj->clean_cache();
	
	$pdf_gen->SetFooter($footer);
	
	// Send contents to pdf gen.
	$pdf_gen->WriteHTML($content);

	// Send pdf and exit script.
	$pdf_gen->Output('observation_list', 'I');
	exit;
?>