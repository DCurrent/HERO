<!--Include: <?php echo __FILE__ . ", Last update: " . date(DATE_ATOM,filemtime(__FILE__)); ?>-->

	<style>
		.checkbox-inline + .checkbox-inline, .radio-inline + .radio-inline {
		  margin-left: 0;
		}
		.columns label.radio-inline, .columns label.checkbox-inline {
		  min-width: 190px;
		  vertical-align: top;
		  width: 30%;
		}
	</style>

		<?php 
			require __DIR__.'/model_location.php'; 
			
			// List queries
			// --Status
			
			// Set up database.
			$form_common_query = new \dc\yukon\Database();			
			
			// --Accounts (Inspector)
			$_obj_field_source_account_list = new \data\Account();
		
			$form_common_query->set_sql('{call account_list_inspector()}');
			$form_common_query->query();
			
			$form_common_query->get_line_params()->set_class_name('\data\Account');
			
			$_obj_field_source_account_list = new SplDoublyLinkedList();
			if($form_common_query->get_row_exists() === TRUE) $_obj_field_source_account_list = $form_common_query->get_line_object_list();	
			
			// --Accounts (Party)
			$_obj_field_source_party_list = new \data\Account();
		
			$form_common_query->set_sql('{call account_list_party()}');
			$form_common_query->query();
			
			$query->get_line_params()->set_class_name('\data\Account');
			
			$_obj_field_source_party_list = new SplDoublyLinkedList();
			if($form_common_query->get_row_exists() === TRUE) $_obj_field_source_party_list = $form_common_query->get_line_object_list();		
			
			// --Event type
			$_obj_data_list_event_type_list = new \data\Common();
		
			$form_common_query->set_sql('{call inspection_status_list()}');
			$form_common_query->query();
			
			$form_common_query->get_line_params()->set_class_name('\data\Common');
			
			$_obj_data_list_event_type_list = new SplDoublyLinkedList();
			if($form_common_query->get_row_exists() === TRUE) $_obj_data_list_event_type_list = $form_common_query->get_line_object_list();
				
			?>
        
        
        
        <!-- Location display. -->
        <?php 
        
            $building_code_display = NULL;
            
            if($_obj_data_sub_area_list->get_building_code())
            {
                $building_code_display = trim($_obj_data_sub_area_list->get_building_code()).' - '.$_obj_data_sub_area_list->get_building_name(); 
            }
        
        ?>        
        
        <div class="form-group">
            <label class="control-label col-sm-2" for="building">Location</label>
            	<div class="col-sm-10">
            		<?php 
						$room_code = trim($_obj_data_sub_area_list->get_room_code());
						if($room_code)
						{
					?>
                            <table class="table table-striped table-condensed">
                                <thead>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody id="tbody_room_data" class="">
                                    <tr>
                                        <td>Area</td>
                                        <td><a href = "area.php?id=<?php echo $room_code;  ?>"
                                        data-toggle	= ""
                                        title		= "View location detail."
                                        target		= "_new" 
                                        ><?php echo $building_code_display; ?></a></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Biosafety Level</td>
                                        <td><?php 
                                            if($_obj_data_sub_area_list->get_biosafety_level())  
                                            {									
                                                echo $_obj_data_sub_area_list->get_biosafety_level();									
                                            }
                                            else
                                            {	
                                            ?>
                                                <span class="glyphicon glyphicon-remove alert-info"></span>
                                            <?php
                                            }
                                            ?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Chemical Lab Class</td>
                                        <td><?php 
                                            if($_obj_data_sub_area_list->get_chemical_lab_class())  
                                            {									
                                                echo $_obj_data_sub_area_list->get_chemical_lab_class();									
                                            }
                                            else
                                            {	
                                            ?>
                                                <span class="glyphicon glyphicon-remove alert-info"></span>
                                            <?php
                                            }
                                            ?>                                    					
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Chemical Operations Class</td>
                                        <td><?php 
                                            if($_obj_data_sub_area_list->get_chemical_operations_class())  
                                            {									
                                                echo $_obj_data_sub_area_list->get_chemical_operations_class();									
                                            }
                                            else
                                            {	
                                            ?>
                                                <span class="glyphicon glyphicon-remove alert-info"></span>
                                            <?php
                                            }
                                            ?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Department</td>
                                        <td><?php echo $_obj_data_sub_area_list->get_department();  ?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Hazardous Waste</td>
                                        <td><?php 
                                            if($_obj_data_sub_area_list->get_hazardous_waste_generated())  
                                            {
                                            ?>									
                                                <span class="glyphicon glyphicon-ok alert-warning"></span>									
                                            <?php
                                            }
                                            else
                                            {	
                                            ?>
                                                <span class="glyphicon glyphicon-remove alert-info"></span>
                                            <?php
                                            }
                                            ?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Radiation Usage</td>
                                        <td><?php 
                                            if($_obj_data_sub_area_list->get_radiation_usage())  
                                            {
                                            ?>									
                                                <span class="glyphicon glyphicon-ok alert-warning"></span>									
                                            <?php
                                            }
                                            else
                                            {	
                                            ?>
                                                <span class="glyphicon glyphicon-remove alert-info"></span>
                                            <?php
                                            }
                                            ?></td>
                                    </tr>
                                    <tr>
                                        <td>X-ray Usage</td>
                                        <td><?php 
                                            if($_obj_data_sub_area_list->get_x_ray_usage())  
                                            {
                                            ?>									
                                                <span class="glyphicon glyphicon-ok alert-warning"></span>									
                                            <?php
                                            }
                                            else
                                            {	
                                            ?>
                                                <span class="glyphicon glyphicon-remove alert-info"></span>
                                            <?php
                                            }
                                            ?></td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                	<?php
						}
						else
						{
					?>
                    		<span class="alert-info">Enter Room Code and save record to display location information.</span>
                    <?php
						}
					?>
                </div>
            </div>
        
        <!-- Room code entry -->                    
        <div class="form-group">
            <label class="control-label col-sm-2" for="room_code">Room Code</label>
            <div class="col-sm-9">
                <input type="text" class="form-control"  name="room_code" id="room_code" placeholder="Room code" value="<?php echo $_obj_data_sub_area_list->get_room_code(); ?>">
            </div>
            
            <div class="col-sm-1">
              <a href="#"
                    class		="btn btn-sm btn-info btn-responsive building_search pull-right" 
                    data-toggle	="modal"
                    title		="Find a room barcode."
                    
                    ><span class="glyphicon glyphicon-search"></span></a>
            </div>
        </div>
        
        <!-- Parties -->
        <div class="form-group">
       	  <div class="col-sm-2">
          </div>                       
          <fieldset class="col-sm-10" >
                <legend>Party Review</legend> 
                                                                  
                <table class="table table-striped table-hover table-condensed" id="tbl_sub_party"> 
                    <thead>
                        <tr>
                            <th><!-- Responsible Party --></th>
                            <th><!-- Party search button --></th>
                            <th><!-- ID, Delete Button --></th>                            
                        </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                    <tbody id="tbody_party" class="parties">                        
                        <?php                              
                        if(is_object($_obj_data_sub_party_list) === TRUE)
                        {        
                            // Generate table row for each item in list.
                            for($_obj_data_sub_party_list->rewind(); $_obj_data_sub_party_list->valid(); $_obj_data_sub_party_list->next())
                            {						
                                $_obj_data_sub_party = $_obj_data_sub_party_list->current();
                            
                                // Blank IDs will cause a database error, so make sure there is a
                                // usable one here.
                                if(!$_obj_data_sub_party->get_item()) $_obj_data_sub_party->set_id(\dc\yukon\DEFAULTS::NEW_ID);
                                
                            ?>
                                <tr>
                                    <td>
                                        <a href="./?id_form=1256&amp;id=<?php echo $_obj_data_sub_party->get_item(); ?>" target="_blank"><?php echo $_obj_data_sub_party->get_name_l().', '.$_obj_data_sub_party->get_name_f(); ?></a>
                                    </td>
                                    <td>
                                    	<!-- Party search goes here on new row adds  -->
                                    </td>                                  
                                    <td style="width:1px">   
                                    	<input 
                                            type	="hidden" 
                                            name	="sub_party_party[]" 
                                            id		="sub_party_party_<?php echo $_obj_data_sub_party->get_item(); ?>" 
                                            value	="<?php echo $_obj_data_sub_party->get_item(); ?>" />
                                            
                                        <button 
                                            type	="button" 
                                            class 	="btn btn-danger btn-sm pull-right" 
                                            name	="sub_party_row_del" 
                                            id		="sub_party_row_del_<?php echo $_obj_data_sub_party->get_item(); ?>" 
                                            onclick="deleteRow_sub_party(this)"><span class="glyphicon glyphicon-minus"></span></button>        
                                    </td>
                                </tr>                                    
                        <?php
                            }
                        }
                        ?>                        
                    </tbody>                        
                </table>                            
                
                <button 
                    type	="button" 
                    class 	="btn btn-success" 
                    name	="row_add" 
                    id		="row_add_party"
                    title	="Add new item."
                    onclick	="insRow_party()">
                    <span class="glyphicon glyphicon-plus"></span></button>
                
            </fieldset>    
        </div>  
                               
        <!--<div class="form-group">
            <label class="control-label col-sm-2" for="name">Label:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control"  name="label" id="label" placeholder="Inspection Title" value="<?php echo $_main_data->get_label(); ?>">
            </div>
        </div>-->
        
        <!--Details-->
            <div class="form-group">                    	
                <div class="col-sm-offset-2 col-sm-10">
                    <fieldset>
                        <legend>Findings</legend>                                
                        <table class="table table-striped table-hover table-condensed" id="tbl_sub_finding"> 
                            <thead>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody class="tbody_finding">                        
                                <?php                              
                                if(is_object($_obj_data_sub_detail_list) === TRUE)
                                {   
                                    $details_counter = 0;
									
                                    //////////
                                    // Audit item query. Since we are constructing markup as we go, 
                                    // there's no getting around multiple executions, so we'll 
                                    // prepare the query here with bound parameters for
                                    // maximum speed and efficiency.
                                    
                                    // Bound parameters.
                                    $query_audit_items_params			= array();
                                    $query_audit_items_param_category 	= NULL;		
                                    
                                    // Set up a query object and send SQL string.
                                    $query_audit_items = new \dc\yukon\Database();
                                    $query_audit_items->set_sql('{call inspection_question_list_select(@category 	= ?,
                                                                                        @inclusion	= ?)}');
                                    
                                    // Set up bound parameters.
                                    $query_audit_items_params = array(array(&$query_audit_items_param_category, SQLSRV_PARAM_IN),
                                                                    array(&$inspection_type, SQLSRV_PARAM_IN));
                                    
                                    // Prepare query for execution.
                                    $query_audit_items->set_params($query_audit_items_params);
                                    $query_audit_items->prepare();
                                     
                                    // Generate table row for each item in list.
                                    for($_obj_data_sub_detail_list->rewind(); $_obj_data_sub_detail_list->valid(); $_obj_data_sub_detail_list->next())
                                    {	
										$details_counter++;
										
                                        $_obj_data_sub_detail = $_obj_data_sub_detail_list->current();
                                    
                                        // Blank IDs will cause a database error, so make sure there is a
                                        // usable one here.
                                        if(!$_obj_data_sub_detail->get_id_key()) $_obj_data_sub_party->set_id(\dc\yukon\DEFAULTS::NEW_ID);
                                        
                                    ?>
                                        <tr>
                                            <td> 
                                            	<!-- Correction/finding -->
                                                <div class="form-group">
                                                    <label class="control-label col-sm-1" for="sub_detail_correction_<?php echo $_obj_data_sub_detail->get_id_key(); ?>" title="Finding."><span class="glyphicon glyphicon-wrench"></span></label>
                                                    <div class="col-sm-11">	
                                                    	<?php echo $_obj_data_sub_detail->get_finding(); ?>								
                                                        <input
                                                            type	= "hidden"
                                                            name 	= "sub_detail_correction[]"
                                                            id		= "sub_detail_correction_<?php echo $_obj_data_sub_detail->get_id_key(); ?>" 	
                                                            value 	= <?php echo $_obj_data_sub_detail->get_correction(); ?> />								   
                                                    </div>
                                                </div> 
                                                
                                                <!-- Details -->
                                                <?php
													if($_obj_data_sub_detail->get_details())
													{
												?>
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-1" for="sub_detail_details_<?php echo $_obj_data_sub_detail->get_id_key(); ?>" title="Details and notes."><span class="glyphicon glyphicon-list-alt"></span></label>
                                                            <div class="col-sm-11">	
                                                                <?php echo $_obj_data_sub_detail->get_details(); ?>	
                                                            </div>
                                                        </div>
                                            	<?php
													}
												?>
                                           		
                                           		<div class="form-group">                       
                                            		<label class="control-label col-sm-1" for="sub_detail_complete_<?php echo $_obj_data_sub_detail->get_id_key(); ?>" title="Complete: Select to indicate this particular correction has been rectified."><span class="glyphicon glyphicon-ok"></span></label>
                                                    <div class="col-sm-11">
                                                    	<?php
															$finding_complete_0 = NULL;
															$finding_complete_1 = NULL;
															
															if($_obj_data_sub_detail->get_complete())
															{
																$finding_complete_1 = 'selected';
															}
															else
															{
																$finding_complete_0 = 'selected';
															}
														?>       
                                                        <select name="sub_detail_complete[]"
                                                        		id	="sub_detail_complete_<?php echo $_obj_data_sub_detail->get_id_key(); ?>">
															<option value = "0" <?php echo $finding_complete_0; ?>>Not Complete</option>
                                                    		<option value = "1" <?php echo $finding_complete_1; ?>>Complete</option>
														</select>
                                                    </div>
                                                </div>
                                            </td>               
                                                  
                                            <td>
                                            	<textarea
													name 	= "sub_detail_details[]"
													id		= "sub_detail_details_<?php echo $_obj_data_sub_detail->get_id_key(); ?>"
													style	= "display:none"><?php echo $_obj_data_sub_detail->get_details(); ?></textarea>
                                                                    
                                            	<input 
                                                    type	="hidden" 
                                                    name	="sub_detail_id[]" 
                                                    id		="sub_detail_id_<?php echo $_obj_data_sub_detail->get_id_key(); ?>" 
                                                    value	="<?php echo $_obj_data_sub_detail->get_id_key(); ?>" />
                                                
                                                <button 
                                                    type	="button" 
                                                    class 	="btn btn-danger btn-sm pull-right" 
                                                    name	="sub_detail_row_del" 
                                                    id		="sub_detail_row_del_<?php echo $_obj_data_sub_detail->get_id_key(); ?>" 
                                                    title	="Remove this item."
                                                    onclick="deleteRow_sub_finding(this)"><span class="glyphicon glyphicon-minus"></span></button> 
                                                        
                                            </td>                                            
                                        </tr>                                    
                                <?php
                                    }
                                }
                                ?>                        
                            </tbody>                        
                        </table>                            
                        
                        <button 
                            type	="button" 
                            class 	="btn btn-success" 
                            name	="row_add" 
                            id		="row_add_detail"
                            title	="Add new item."
                            onclick	="insRow_finding()">
                            <span class="glyphicon glyphicon-plus"></span></button>
                    </fieldset>
                </div>                        
            </div>
 		<!--/Details-->
 
 		<div class="form-group" id="fg_audits">       
       	  <div class="col-sm-2">
          </div>                
          <fieldset class="col-sm-10">
                <legend>Audits</legend>
                                                
                <table class="table table-striped table-hover table-condensed" id="tbl_sub_visit"> 
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>By</th>  
                            <th>Time</th>
                            <th></th>                            
                        </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                    <tbody id="tbody_visit" class="visit">                        
                        <?php                              
                        if(is_object($_obj_data_sub_visit_list) === TRUE)
                        {        
                            // Generate table row for each item in list.
                            for($_obj_data_sub_visit_list->rewind(); $_obj_data_sub_visit_list->valid(); $_obj_data_sub_visit_list->next())
                            {						
                                $_obj_data_sub_visit = $_obj_data_sub_visit_list->current();
                            
                                // Blank IDs will cause a database error, so make sure there is a
                                // usable one here.
                                if(!$_obj_data_sub_visit->get_id_key()) $_obj_data_sub_visit->set_id(\dc\yukon\DEFAULTS::NEW_ID);
                                
                            ?>
                                <tr>
                                    <td><a href="./?id_form=1599&amp;id=<?php echo $_obj_data_sub_visit->get_visit_type(); ?>" target="_blank"><?php echo $_obj_data_sub_visit->get_visit_type_label(); ?></a>
                                        <input type="hidden"
                                            name 	= "sub_visit_type[]"
                                            id		= "sub_visit_type_<?php echo $_obj_data_sub_visit->get_id_key(); ?>"
                                            value	="<?php echo $_obj_data_sub_visit->get_visit_type(); ?>" />                                       
                                    </td>  
                                    
                                    <td><a href="./?id_form=1256&amp;id=<?php echo $_obj_data_sub_visit->get_visit_by(); ?>" target="_blank"><?php echo $_obj_data_sub_visit->get_name_l().', '.$_obj_data_sub_visit->get_name_f(); ?></a>   
                                    	<input type="hidden"
                                            name 	= "sub_visit_by[]"
                                            id		= "sub_visit_by_<?php echo $_obj_data_sub_visit->get_id_key(); ?>"
                                            value	="<?php echo $_obj_data_sub_visit->get_visit_by(); ?>" />                                     
                                        
                                    </td>
                                    
                                    <td><?php $visit_time = NULL;
										if($_obj_data_sub_visit->get_time_recorded()) 
										{
											$visit_time = date(APPLICATION_SETTINGS::TIME_FORMAT, $_obj_data_sub_visit->get_time_recorded()->getTimestamp());                                        }
                                        ?> 
                                        <?php echo $visit_time; ?>   
                                        <input 	type="hidden"                                                        	 
                                            name	="sub_visit_time_recorded[]" 
                                            id		="sub_visit_time_recorded_<?php echo $_obj_data_sub_visit->get_id_key(); ?>" 
                                            value 	= "<?php echo $visit_time; ?>">
                                    </td>
                                                                                  
                                    <td style="width:1px">													
                                        <input 
                                            type	="hidden" 
                                            name	="sub_visit_id[]" 
                                            id		="sub_visit_id_<?php echo $_obj_data_sub_visit->get_id_key(); ?>" 
                                            value	="<?php echo $_obj_data_sub_visit->get_id_key(); ?>" />
                                        <button 
                                            type	="button" 
                                            class 	="btn btn-danger btn-sm pull-right" 
                                            name	="sub_visit_row_del" 
                                            id		="sub_visit_row_del_<?php echo $_obj_data_sub_visit->get_id_key(); ?>" 
                                            onclick="deleteRow_sub_visit(this)"><span class="glyphicon glyphicon-minus"></span></button>        
                                    </td>
                                </tr>                                    
                        <?php
                            }
                        }
                        ?>                        
                    </tbody>                        
                </table>                            
                
                <button 
                    type	="button" 
                    class 	="btn btn-success" 
                    name	="row_add" 
                    id		="row_add_perm"
                    title	="Add new item."
                    onclick	="insRow_visit()">
                    <span class="glyphicon glyphicon-plus"></span></button>
            </fieldset>
        </div><!--/fg_audits-->
 
 <script src="../../libraries/javascript/options_update.js"></script>
 
 <script>
 
 	$(document).ready(function(event) {		
				
				
				options_update(event, null, '#facility');
					
			});
 
 	// Room search and add.
	$('.facility_filter').change(function(event)
	{
		options_update(event, null, '#facility');	
	});
 
 	// Room search and add.
	$('.room_search').change(function(event)
	{
		options_update(event, null, '#area');	
	});
  
	$(".building_search").click(function(event){
			
		// Need to populate the model with building drop down.
		//options_update(event, null, '#facility');
		
		//options_update(event, null, '#building_code');
		options_update(event, null, '#area');
		
		$(".modal_building_search").modal();
	});
		
	$('.room_code_insert').click((function() {
	
		$('input[name="room_code"]').val($('.room_code_search').val());
	
	}));
 
 	// Party add/remove.
 	var $temp_id_party = 0;	// Temp id for new party rows.
 
 	// Remove a party row.
 	function deleteRow_sub_party(row)
	{
		var i=row.parentNode.parentNode.rowIndex;
		document.getElementById('tbl_sub_party').deleteRow(i);
	}
 
 	// Inserts a new party row.
	function insRow_party()
	{			
		$('.parties').append(
			'<tr>'
				+'<td>'
					+'<select '
						+'name 	= "sub_party_party[]" '
						+'id	= "sub_party_party_'+$temp_id_party+'" '
						+'class	= "form-control">'
						+'<option value="" selected>Select Party</option> '
						<?php																
						if(is_object($_obj_field_source_party_list) === TRUE)
						{        
							// Generate table row for each item in list.
							for($_obj_field_source_party_list->rewind();	$_obj_field_source_party_list->valid(); $_obj_field_source_party_list->next())
							{	                                                               
								$_obj_field_source_party = $_obj_field_source_party_list->current();
																								
								$sub_party_label		= $_obj_field_source_party->get_name_l().', '.$_obj_field_source_party->get_name_f();
								
								// Add middle name if available
								if($_obj_field_source_party->get_name_m())
								{
									$sub_party_label .= ' '.$_obj_field_source_party->get_name_m();
								}
								
								$sub_party_label = htmlspecialchars($sub_party_label, ENT_QUOTES);
								
								?>
								+'<option value="<?php echo $_obj_field_source_party->get_id(); ?>"><?php echo $sub_party_label; ?></option>'
								<?php                                
							}
						}
					?>
					+'</select>'												
				+'</td>'  
				
				+'<td style="width:1px">'
					+'<a href="#" '
							+'class			="btn btn-sm btn-info btn-responsive party_search" '
							+'data-toggle	="modal" '
							+'data-confirm_target_id="sub_party_party_'+$temp_id_party+'" '
							+'title			="Find a party selection." '                                               
							+'><span class="glyphicon glyphicon-search"></span></a>'
				+'</td>'
				
				+'<td style="width:1px">'													
					+'<input ' 
						+'type	="hidden" ' 
						+'name	="sub_party_id[]" ' 
						+'id	="sub_party_id_'+$temp_id_party+'" ' 
						+'value	="<?php echo \dc\yukon\DEFAULTS::NEW_ID; ?>" />'
						
					+'<button ' 
						+'type	="button" ' 
						+'class ="btn btn-danger btn-sm pull-right" ' 
						+'name	="sub_party_row_del" ' 
						+'id	="sub_party_row_del_'+$temp_id_party+'" ' 
						+'onclick="deleteRow_sub_party(this)"><span class="glyphicon glyphicon-minus"></span></button>'        
				+'</td>'
			+'</tr>'
		
		);
			
			$temp_id_party--;
	}
	
	// Visit add/remove
	
	var $temp_id_visit = 0;
	
	function deleteRow_sub_visit(row)
	{
		var i=row.parentNode.parentNode.rowIndex;
		document.getElementById('tbl_sub_visit').deleteRow(i);
	}
	
	function insRow_visit()
	{			
		$('.visit').append(
			'<tr>'
				+'<td>'
					+'<select '
						+'name 	= "sub_visit_type[]" '
						+'id	= "sub_visit_type_'+$temp_id_visit+'" '
						+'class	= "form-control"> '
						+'<option value="" selected>Select Type</option> '							
						<?php
							if(is_object($_obj_data_list_event_type_list) === TRUE)
							{        
								// Generate table row for each item in list.
								for($_obj_data_list_event_type_list->rewind();	$_obj_data_list_event_type_list->valid(); $_obj_data_list_event_type_list->next())
								{	                                                               
									$_obj_data_list_event_type = $_obj_data_list_event_type_list->current();
									
									?>
									+'<option value="<?php echo $_obj_data_list_event_type->get_id(); ?>" ><?php echo $_obj_data_list_event_type->get_label(); ?></option>'
									<?php                                
								}
							}
						?>
						
					+'</select>'						
				+'</td>'  
				
				+'<td>'			                                          
					+'<select '
						+'name 	= "sub_visit_by[]" '
						+'id	= "sub_visit_by_'+$temp_id_visit+'" '
						+'class	= "form-control">'							
						<?php							
						
						// Set up account info.
						$access_obj = new \dc\access\status();
											
						if(is_object($_obj_field_source_account_list) === TRUE)
						{        
							// Generate table row for each item in list.
							for($_obj_field_source_account_list->rewind();	$_obj_field_source_account_list->valid(); $_obj_field_source_account_list->next())
							{	                                                               
								$_obj_field_source_account = $_obj_field_source_account_list->current();
								
								$sub_account_value 		= $_obj_field_source_account->get_id();																
								$sub_account_label		= $_obj_field_source_account->get_name_l().', '.$_obj_field_source_account->get_name_f();
								$sub_account_selected 	= NULL;
								
								if($_obj_field_source_account->get_account() == $access_obj->get_account())
								{
									$sub_account_selected = ' selected ';
								}									
								
								?>
								+'<option value="<?php echo $sub_account_value; ?>" <?php echo $sub_account_selected ?>><?php echo $sub_account_label; ?></option>'
								<?php                                
							}
						}
					?>
					+'</select>'
				+'</td>'
				
				+'<td>'                                                    	
					+'<input 	type="text" '                                                        	 
						+'name	= "sub_visit_time_recorded[]" ' 
						+'id	= "sub_visit_time_recorded_'+$temp_id_visit+'" ' 
						+'class	= "form-control" '
						+'value = "<?php echo date(APPLICATION_SETTINGS::TIME_FORMAT); ?>">'
				+'</td>'
															  
				+'<td style="width:1px">'													
					+'<input ' 
						+'type	="hidden" ' 
						+'name	="sub_visit_id[]" ' 
						+'id	="sub_visit_id_'+$temp_id_visit+'" ' 
						+'value	="<?php echo \dc\yukon\DEFAULTS::NEW_ID; ?>" />'
				
					+'<button ' 
						+'type	="button" ' 
						+'class ="btn btn-danger btn-sm pull-right" ' 
						+'name	="sub_visit_row_del" ' 
						+'id	="sub_visit_row_del_'+$temp_id_visit+'" ' 
						+'onclick="deleteRow_sub_visit(this)"><span class="glyphicon glyphicon-minus"></span></button>'        
				+'</td>'
			+'</tr>'
		
		);
		
		$temp_id_visit--;		
		
	}
	
	var $temp_finding = 0;
            
            function deleteRow_sub_finding(row)
            {
                var i=row.parentNode.parentNode.rowIndex;
                document.getElementById('tbl_sub_finding').deleteRow(i);
            }
            
            function insRow_finding()
            {                
                $('.tbody_finding').append(
                    '<tr>'
                        +'<td>'
                            +'<div class="form-group">'
                                +'<label class="control-label col-sm-1" for="sub_detail_category_'+$temp_finding+'" title="Category Filter: Choose an item to filter the available selections in Correction List by category."><span class="glyphicon glyphicon-filter"></span></label> '
                                +'<div class="col-sm-11">'
                                                                                            
                                    +'<select '
                                        +'name 		= "sub_detail_category[]" '
                                        +'id		= "sub_detail_category_'+$temp_finding+'" '
                                        +'class		= "form-control" '
                                        +'onChange 	= "update_corrections(this)"> '
                                        +'<?php echo $category_list_options; ?> '
                                    +'</select>'
                                +'</div>'
                            +'</div>'
                        
                            +'<div class="form-group"> '
                                +'<label class="control-label col-sm-1" for="sub_detail_correction_'+$temp_finding+'" title="Correction: Choose the nessesary correction here."><span class="glyphicon glyphicon-wrench"></span></label> '
                                +'<div class="col-sm-11">'								
                                    
                                    +'<select '
                                        +'name 	= "sub_detail_correction[]" '
                                        +'id	= "sub_detail_correction_'+$temp_finding+'" '
                                        +'class	= "form-control update_source_sub_detail_category_'+$temp_finding+'"> '                                        
                                        +'<?php echo $correction_list_options; ?>'                                        
                                    +'</select>'
                                +'</div>'
                            +'</div>'                                                        
                            
                            +'<div class="form-group" id="div_sub_detail_details_'+$temp_finding+'">'
                                +'<label class="control-label col-sm-1" for="sub_detail_details_'+$temp_finding+'" title="Comments: Add any specific comments or notes here."><span class="glyphicon glyphicon-list-alt"></span></label> '
                                +'<div class="col-sm-11">'
                                    +'<textarea ' 
                                        +'class	= "form-control" ' 
                                        +'rows 	= "5" ' 
                                        +'name	= "sub_detail_details[]" ' 
                                        +'id	= "sub_detail_details_'+$temp_finding+'"></textarea>'
                                +'</div>'
                            +'</div>'
                        
							+'<div class="form-group"> '                      
								+'<label class="control-label col-sm-1" for="sub_detail_complete_'+$temp_finding+'" title="Complete: Select to indicate this particular correction has been rectified."><span class="glyphicon glyphicon-ok"></span></label> '
								+'<div class="col-sm-11"> '								 
									+'<select name="sub_detail_complete[]" id ="sub_detail_complete_'+$temp_finding+'">'
										+'<option value = "0" selected>Not Complete</option>'
										+'<option value = "1">Complete</option>'
									+'</select>'
								+'</div>'
							+'</div>'
					
						+'</td>'
                        
                        +'<td>'
							+'<input ' 
                                +'type	= "hidden" ' 
                                +'name	= "sub_detail_id[]" ' 
                                +'id	= "sub_detail_id_'+$temp_finding+'" ' 
                                +'value	= "<?php echo \dc\yukon\DEFAULTS::NEW_ID; ?>" />'
								
                            +'<button ' 
                                +'type	= "button" ' 
                                +'class = "btn btn-danger btn-sm" ' 
                                +'name	= "sub_detail_row_del" ' 
                                +'id	= "sub_detail_row_del_'+$temp_finding+'" ' 
                                +'onclick = "deleteRow_sub_finding(this)"><span class="glyphicon glyphicon-minus"></span></button>'       
                        +'</td>'
                    +'</tr>'			
                );
                
                tinymce.init({
                selector: '#sub_detail_details_'+$temp_finding,
                plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"});
        
                
                $temp_finding--;
            }
            
            // Update correction list items based on category selection.
            function update_corrections($val)
            {
                var $update_select_id = ".update_source_" + $($val).attr('id');	
				var $category = null;
		
				           
                // Get element by css seelctor - this returns a list.
                var $target = document.querySelectorAll($update_select_id);
        
                // Iterate list and update al elements (In most cases, there will
                // only be one).
                for (var i = 0; i < $($target).length; i++) 
                {
                    $($target).attr('disabled', false);
                    
					$($target).load('<?php echo APPLICATION_SETTINGS::DIRECTORY_PRIME; ?>/inspection_saa_corrections_list.php?category=' + $($val).val() + '&inclusion=<?php echo $inspection_type; ?>');
                }			
            }
</script>
<!--/Include: <?php echo __FILE__; ?>-->