<?php 
	
	require(__DIR__.'/source/main.php');
	require(__DIR__.'/source/common_functions/common_security.php');
	require('../../libraries/vendor/mpdf/mpdf.php');	// pdf maker.
	
	const LOCAL_STORED_PROC_NAME 	= 'stf_observation_target_read'; 	// Used to call stored procedures for the main record set of this script.
	const LOCAL_BASE_TITLE 			= 'Observation Checklist';			// Title display, button labels, instruction inserts, etc.
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
	$yukon_database->set_param_array($params);
	$yukon_database->query_run();
	
	// Get navigation record set and populate navigation object.		
	$yukon_database->get_line_config()->set_class_name('\dc\recordnav\RecordNav');	
	if($yukon_database->get_row_exists() === TRUE) $obj_navigation_rec = $yukon_database->get_line_object();	
	
	// Get primary data record set.	
	$yukon_database->get_next_result();
	
	$yukon_database->get_line_config()->set_class_name($primary_data_class);	
	if($yukon_database->get_row_exists() === TRUE) $_main_data = $yukon_database->get_line_object();	
	
	// Sub - Party.
	$yukon_database->get_next_result();
	
	$yukon_database->get_line_config()->set_class_name('\data\ObservationSource');
	
	$_list_observation_source = new SplDoublyLinkedList();
	if($yukon_database->get_row_exists()) $_list_observation_source = $yukon_database->get_line_object_list();

?>
<html lang="en">
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <title><?php echo APPLICATION_SETTINGS::NAME. ', '.LOCAL_BASE_TITLE; ?></title>        
        
        <link rel="stylesheet" href="source/bootstrap/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>     
        <script src="source/bootstrap/script.js"></script>		
    	
    	<style>
			.printable { border-spacing: 15px; } /* cellspacing */
		</style>
    
    </head>
    
    <body style="font-size: 14pt;">    
        <div id="container" class="container">                                              
            <div class="page-header">           
                <h1><?php echo LOCAL_BASE_TITLE; ?></h1>
                <p class="lead">This printable observation form is provided for your convenience. Completed observations must be submitted with the <?php echo APPLICATION_SETTINGS::NAME; ?> Application.</p>
            </div>
            
          		<p class="lead">Lab/Area:&nbsp;_________________________</p>
							
				<table class="table printable" id="tbl_sub_visit" cellspacing="5" cellpadding="5"> 
					<thead>								
					</thead>
					<tfoot>
					</tfoot>
					<tbody id="tbody_observations" class="">                        
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
									<th><?php echo $observation_count+1; ?>.&nbsp;</th>
									<td><?php echo $_observation_source_current->get_observation(); ?><td>&nbsp;&nbsp;Yes:&nbsp;___&nbsp;&nbsp;No:&nbsp;___</td>					
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
              
			<p class="lead">Additional Observations:</p>
           
        </div><!--container-->        
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