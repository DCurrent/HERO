<!--Include: <?php echo __FILE__ . ", Last update: " . date(DATE_ATOM,filemtime(__FILE__)); ?>-->

    <!-- Room code search modal -->
    <div id="building_search" class="modal fade modal_building_search" role="dialog">
        <div class="modal-dialog">                
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Room Code Search</h4>
                    
                    <p>Select a building, then choose a room. When you are finished, press Insert to populate the Room Code field with your room selection. If needed, type a few letters from a facility name into the <span class="text-info">filter</span> field to filter available facility selections.</p>
                </div>
                
                <?php
                
                    // Populate the facility select.
                    //
                    // If the current record has a building/room selected, then
                    // we will populate Facility Select with that value.
                    // Otherwise we'll check to see if the "last selected"
                    // variable has been populated, and if so use it.
                    //
                    // If none of these can be found, then there is no default
                    // value to be had.
                    if($_obj_data_sub_area_list->get_building_code())
                    {			
                        $building_selection = $_obj_data_sub_area_list->get_building_code();
                    }
                    else
                    {
                        // Verify the last selected session var exisits, and
                        // if it does, use it as our selected value.
                        if(isset($_SESSION[SESSION_ID::LAST_BUILDING]) == TRUE)
                        {
                            $building_selection = $_SESSION[SESSION_ID::LAST_BUILDING];
                        }
                    }
                ?>
                                     
                <div class="modal-body"> 
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="filter">Filter</label>
                        <div class="col-sm-10">
                            <input name="filter" 
                                list="browsers"
                                id="filter" 
                                data-current=""                            
                                class="facility_filter form-control">
                        </div>
                    </div>
                                                     
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="facility">Facility</label>
                        <div class="col-sm-10">
                            <select name="facility" 
                                id="facility" 
                                data-current="<?php echo $building_selection; ?>" 
                                data-source-url="../../libraries/inserts/facility.php" 
                                data-extra-options='<option value="">Select Facility</option>'
                                data-grouped="1"
                                class="room_search form-control">
                                    <!--This option is for valid HTML5; it is overwritten on load.--> 
                                    <option value="0">Select Facility</option>                                    
                                    <!--Options will be populated on load via jquery.-->                                 
                            </select>
                        </div>
                    </div> 
                    
                    <br />
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="area">Area</label>
                        <div class="col-sm-10">
                            <select name="area" 
                                id="area" 
                                data-current="<?php echo $_obj_data_sub_area_list->get_room_code(); ?>" 
                                
                                data-source-url="../../libraries/inserts/room.php" 
                                data-grouped="1" 
                                data-extra-options='<option value="">Select Room/Area/Lab</option>' 
                                class="room_code_search disable form-control" 
                                disabled>                                        
                                    <!--Options will be populated/replaced on load via jquery.-->
                                    <option value="0">Select Room/Area/Lab</option>                                  							
                            </select> 
                        </div>                                   
                    </div>
                    
                    <br />
                    <br />                           
                </div><!--Model body-->
                
                <div class="modal-footer">
                    <button type="button" class="room_code_insert btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-save"></span> Insert</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>                
        </div>
    </div><!-- #building_search -->
<!--/Include: <?php echo __FILE__; ?>-->