<?php
	
	namespace dc\recordnav;
	
	require_once('config.php');
	require_once('DataNavigation.class.php');
	
	class RecordNav extends DataNavigation
	{		
		protected			
			$markup			= NULL,
			$command		= NULL,			
			$action			= NULL,			
			$dialog			= NULL;
			
		private
			$markup_cmd_delete		= NULL,
			$markup_cmd_first		= NULL,
			$markup_cmd_last		= NULL,
			$markup_cmd_new_blank	= NULL,
			$markup_cmd_new_copy	= NULL,
			$markup_cmd_next		= NULL,
			$markup_cmd_previous	= NULL,
			$markup_cmd_save		= NULL,
			$markup_cmd_save_block	= NULL;		
					
		public function __construct()
		{		
			$this->populate_from_request();	
		}
				
		// Create delete command markup.
		public function generate_command_delete()
		{	
			$result 	= NULL;
			$id			= NULL;
			$disabled 	= NULL;
			
			// Get id we'll be using.
			$id 		= $this->id;	
			
			$url_query	= new \dc\url\URLFix;
			$url_query->set_data('action', \dc\recordnav\COMMANDS::DELETE);
			
			if($this->id == \dc\yukon\DEFAULTS::NEW_ID) $disabled = ' disabled';
				
			// Start caching.
			ob_start()
			
			?>
            <!-- Delete modal -->
            <div id="delete_<?php echo $this->id; ?>" class="modal fade" role="dialog">
                <div class="modal-dialog">                
                <!-- Modal content-->
                	<div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Confirm Delete</h4>
                        </div>
                        <div class="modal-body">
                            <p>If you delete this record it cannot be undone. Are you sure?</p>
                        </div>
                        <div class="modal-footer">                     
                            <a href="<?php echo $url_query->return_url_encoded(); ?>"
                                class		="btn btn-danger btn-responsive" 
                                
                                title		="Confirm delete."                                
                                ><span class="glyphicon glyphicon-trash"></span> Delete</a>
                            
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>                
                </div>
            </div><!-- #delete_<?php echo $this->id; ?> --> 
            
			<a href="#"
                    class		="btn btn-danger btn-responsive <?php echo $disabled ?>" 
                    data-toggle	="modal"
                    title		="Delete this record."
                    data-target	="#delete_<?php echo $this->id; ?>"
                    ><span class="glyphicon glyphicon-trash"></span></a>
			<?php
			
			// Get cache contents and clean the cache.
			$result = ob_get_contents();
			ob_end_clean();	
			
			$this->markup_cmd_delete = $result;
			
			return $result;
        }
		
		// Create first command markup.
		public function generate_command_first()
		{	
			$result 	= NULL;
			$id			= NULL;
			$disabled 	= 'disabled';
			$link		= '#';
			
			$url_query	= new \dc\url\URLFix;
			$url_query->set_data('action', \dc\recordnav\COMMANDS::FIRST);
		
		
			// Get id we'll be using.
			$id = $this->id_first;		
			
			$url_query->set_data('id', $id);
			$link = $url_query->return_url_encoded();			
			$disabled = NULL;
			
			
			// Start caching.
			ob_start()
			
			?>               
                <a href="<?php echo $link; ?>"                                                
                    class		="btn btn-primary btn-responsive <?php echo $disabled; ?>" 
                     
                    title		="Go to first record."
                    ><span class="glyphicon glyphicon-fast-backward"></span></a>
			<?php
			
			// Get cache contents and clean the cache.
			$result = ob_get_contents();
			ob_end_clean();	
			
			// Set data member.
			$this->markup_cmd_first = $result;
			
			// Output end result.
			return $result;
        }		
		
		// Create last command markup.
		public function generate_command_last()
		{	
			$result 	= NULL;
			$id			= NULL;
			$disabled 	= 'disabled';
			$link		= '#';
			
			$url_query	= new \dc\url\URLFix;
			$url_query->set_data('action', \dc\recordnav\COMMANDS::LAST);
			
			// Get id we'll be using.
			$id = $this->id_last;	
			
			$url_query->set_data('id', $id);
			$link = $url_query->return_url_encoded();			
			$disabled = NULL;			
				
			// Start caching.
			ob_start()
			
			?>               
                <a href="<?php echo $link; ?>"                                                
                    class		="btn btn-primary btn-responsive <?php echo $disabled; ?>" 
                   
                    title		="Go to last record."
                    ><span class="glyphicon glyphicon-fast-forward"></span></a>
			<?php
			
			// Get cache contents and clean the cache.
			$result = ob_get_contents();
			ob_end_clean();	
			
			// Set data member.
			$this->markup_cmd_last = $result;
			
			// Output end result.
			return $result;
        }
		
		// Create list command markup.
		public function generate_command_list()
		{	
			$result 	= NULL;				
			
			$url_query	= new \dc\url\URLFix;
			$url_query->set_data('action', \dc\recordnav\COMMANDS::LISTING);
			
			// Start caching.
			ob_start()
			
			?>           
			<a href="<?php echo $url_query->return_url_encoded(); ?>"                        
                        class		="btn btn-info btn-responsive" 
                        
                        title		="Switch to list mode."
                        ><span class="glyphicon glyphicon glyphicon-list"></span></a>
			<?php
			
			// Get cache contents and clean the cache.
			$result = ob_get_contents();
			ob_end_clean();	
			
			$this->markup_cmd_last = $result;
			
			return $result;
        }
		
		// Create new (blank) command markup.
		public function generate_command_new_blank()
		{	
			$result 	= NULL;
			$disabled 	= NULL;
			$url_query	= new \dc\url\URLFix;
			$url_query->set_data('action', \dc\recordnav\COMMANDS::NEW_BLANK);
			$url_query->set_data('id', \dc\yukon\DEFAULTS::NEW_ID);
			
			if($this->id == \dc\yukon\DEFAULTS::NEW_ID) $disabled = ' disabled';
							
							
			// Start caching.
			ob_start()
			
			?>               
                <a href="<?php echo $url_query->return_url_encoded(); ?>"                        
                    class		="btn btn-success btn-responsive <?php echo $disabled; ?>" 
                     
                    title		="Start a new blank record."
                    ><span class="glyphicon glyphicon-plus"></span></a>
			<?php
			
			// Get cache contents and clean the cache.
			$result = ob_get_contents();
			ob_end_clean();	
			
			// Set data member.
			$this->markup_cmd_new_blank = $result;
			
			// Output end result.
			return $result;
        }
		
		// Create new (blank) command markup.
		public function generate_command_new_copy()
		{	
			$result 	= NULL;
			$disabled 	= NULL;
			
			$url_query	= new \dc\url\URLFix;
			$url_query->set_data('action', \dc\recordnav\COMMANDS::NEW_COPY);
			$url_query->set_data('id', \dc\yukon\DEFAULTS::NEW_ID);
			
			//if($this->id == \dc\yukon\DEFAULTS::NEW_ID) 
						$disabled = ' disabled';
			
			// Start caching.
			ob_start()
			
			?>                   
                <button 
                    type		="submit" 
                    name		="command"
                    id			="command_<?php echo \dc\recordnav\COMMANDS::NEW_COPY; ?>" 	
                    class		="btn btn-success btn-responsive <?php echo $disabled; ?>" 
                     
                    title		="Create a new copy of this record."
                    value		="<?php echo \dc\recordnav\COMMANDS::NEW_COPY; ?>"
                    formaction	="<?php echo $url_query->return_url_encoded(); ?>"
                    ><span class="glyphicon glyphicon-transfer"></span></button>
			<?php
			
			// Get cache contents and clean the cache.
			$result = ob_get_contents();
			ob_end_clean();	
			
			// Set data member.
			$this->markup_cmd_new_copy = $result;
			
			// Output end result.
			return $result;
        }
		
		// Create next command markup.
		public function generate_command_next()
		{	
			$result 	= NULL;
			$id			= NULL;
			$disabled 	= 'disabled';
			$link		= '#';
			
			$url_query	= new \dc\url\URLFix;
			$url_query->set_data('action', \dc\recordnav\COMMANDS::NEXT);
			
			// Get id we'll be using.
			$id = $this->id_next;
		
			$url_query->set_data('id', $id);
			$link = $url_query->return_url_encoded();		
			$disabled = NULL;
					
				
			// Start caching.
			ob_start()
			
			?>               
                <a href="<?php echo $link; ?>"                                                
                    class		="btn btn-primary btn-responsive <?php echo $disabled; ?>" 
                    title		="Go to next record."
                    ><span class="glyphicon glyphicon-forward"></span></a>
			<?php
			
			// Get cache contents and clean the cache.
			$result = ob_get_contents();
			ob_end_clean();	
			
			// Set data member.
			$this->markup_cmd_next = $result;
			
			// Output end result.
			return $result;
        }
		
		// Create previous command markup.
		public function generate_command_previous()
		{		
			$result 	= NULL;
			$id			= NULL;
			$disabled 	= 'disabled';
			$link		= '#';
			
			$url_query	= new \dc\url\URLFix;
			$url_query->set_data('action', \dc\recordnav\COMMANDS::PREVIOUS);
			
			// Get id we'll be using.
			$id = $this->id_previous;
		
			// If we're working on a new record, we'll act as if the new record is last in order,
			// so the previous button should go "back" to last exisiting record.
			if($this->id == \dc\yukon\DEFAULTS::NEW_ID) $id = $this->id_last;			
				
			$url_query->set_data('id', $id);
			$link =	$url_query->return_url_encoded();	
			$disabled = NULL;						
				
			// Start caching.
			ob_start()
			
			?>               
                <a href="<?php echo $link; ?>"                                                
                    class		="btn btn-primary btn-responsive <?php echo $disabled; ?>" 
                    title		="Go to previous record."
                    ><span class="glyphicon glyphicon-backward"></span></a>
			<?php
			
			// Get cache contents and clean the cache.
			$result = ob_get_contents();
			ob_end_clean();	
			
			// Set data member.
			$this->markup_cmd_previous = $result;
			
			// Output end result.
			return $result;
        }
		
		// Create save command markup.
		public function generate_command_save()
		{	
			$url_query	= new \dc\url\URLFix;
			$url_query->set_data('action', \dc\recordnav\COMMANDS::SAVE);
			
			$result 	= NULL;
				
			// Start caching.
			ob_start()
			
			?>
			<div class="btn-group">
				<button 
						type		="submit" 
						name		="command"                     	
						class		="btn btn-warning btn-responsive record_save" 
						title		="Save this record."
						value		="<?php echo \dc\recordnav\COMMANDS::SAVE; ?>"
						formaction	="<?php echo $url_query->return_url_encoded(); ?>"
						><span class="glyphicon glyphicon-floppy-disk"></span></button>
			</div>
			<?php
			
			// Get cache contents and clean the cache.
			$result = ob_get_contents();
			ob_end_clean();	
			
			$this->markup_cmd_save = $result;
			
			return $result;
        }
		
		// Create save block command markup.
		public function generate_command_save_block()
		{	
			$url_query	= new \dc\url\URLFix;
			$url_query->set_data('action', \dc\recordnav\COMMANDS::SAVE);			
			
			$result 	= NULL;
				
			// Start caching.
			ob_start()
			
			?>
			<button 
                    type		="submit"
                    name		="command"                    	
                    class		="btn btn-warning btn-block record_save" 
                     
                    value		="<?php echo \dc\recordnav\COMMANDS::SAVE; ?>"
                    formaction	="<?php echo $url_query->return_url_encoded(); ?>"
                    ><span class="glyphicon glyphicon-floppy-disk"></span> Save This Item</button>
			<?php
			
			// Get cache contents and clean the cache.
			$result = ob_get_contents();
			ob_end_clean();	
			
			$this->markup_cmd_save_block = $result;
			
			return $result;
        }
				
		// Generate and return record navigation list.
		public function generate_button_list($first = TRUE, $previous = TRUE, $new_blank = TRUE, $new_copy = FALSE, $save = TRUE, $list = TRUE, $delete = TRUE, $next = TRUE, $last = TRUE)
		{
			$result = NULL;
			
			// Start caching.
			ob_start()
			?>     
            <p><?php echo $this->dialog; ?></p>                       
                            
            <div class="btn-group btn-group-justified" >
                       
                <?php
					if($first === TRUE) 	echo $this->generate_command_first();
					if($previous === TRUE)	echo $this->generate_command_previous();
					if($new_blank === TRUE)	echo $this->generate_command_new_blank();
					if($new_copy === TRUE)	echo $this->generate_command_new_copy();
					if($save === TRUE)		echo $this->generate_command_save();
					if($list === TRUE)		echo $this->generate_command_list();
					if($delete === TRUE)	echo $this->generate_command_delete();
					if($next === TRUE)		echo $this->generate_command_next();
					if($last === TRUE)		echo $this->generate_command_last();			
				?>                   
            </div>               
             
			<?php
			
			$this->generate_command_save_block();
			
			// Get cache contents and clean the cache.
			$result = ob_get_contents();
			ob_end_clean();	
			
			// Send results to data member.
			$this->markup = $result;
			
			return $result;
		}	
				
		// Accessors
		public function get_action()
		{
			return $this->action;
		}
		
		public function get_command()
		{
			return $this->command;
		}
		
		public function get_markup()
		{
			return $this->markup;
		}
		
		public function get_markup_cmd_save_block()
		{
			return $this->markup_cmd_save_block;
		}
		
		public function get_dialog()
		{
			return $this->dialog;
		}
		
		// Mutators	
		public function set_action($value)
		{			
			$this->action = $value;
		}
		
		public function set_command($value)
		{
			$this->command = $value;
		}	
		
		public function set_dialog($value)
		{
			$this->dialog = $value;
		}
	}	
	
?>
