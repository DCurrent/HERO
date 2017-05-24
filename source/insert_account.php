<?php	

	require(__DIR__.'/main.php');
	
	class blair_account_select()
	{
		private
			$markup = NULL,
			$query	= NULL;
			
		public function __construct()
		{
			// Initialize DB connection and query objects.	
			$db_conn_set = new ConnectConfig();
			$db_conn_set->set_name(DATABASE::NAME);
	
			$db = new Connect($db_conn_set);
			$query = new \dc\yukon\Database($db);
		}
			
		public function write
			
	}

	function write($_obj_data_list, $selected)
	{
		$value 		= NULL;
		$label 		= NULL;
		$selected	= NULL;	
		$_obj_data	= NULL;			
					
		// Iterate through array of result objects.
		for($_obj_data_list->rewind();	$_obj_data_list->valid(); $_obj_data_list->next())
		{
			$_obj_data = $_obj_data_list->current();
			
			$label		= $_obj_data->get_name_l().', '.$_obj_data->get_name_f();
			$value		= $_obj_data->get_id();				
			$selected 	= NULL;
			
			if($selected && $selected == $value)
			{
				$selected = ' selected ';
			}
			
			// Add middle name if present.
			if($_obj_data->get_name_m())
			{
				$label .= ' '.$_obj_data->get_name_m();
			}
			
			?>
			
			<option value="<?php echo $value; ?>" <?php echo $selected ?>><?php echo $label; ?></option>
			
			<?php
		}
	}
	
	class post
	{
		private
			$col_order,
			$code,
			$name,
			$address,
			$filter,
			$current;
		
		public function __construct() 
		{		
			// Interate through each class variable.
       		foreach($this as $key => $value) 
			{			
				// If we can find a matching a post var with key matching
				// key of current object var, set object var to the post value. 
				if(isset($_POST[$key]))
				{					
					$this->$key = $_POST[$key];           						
				}
			}	
	 	}
		
		public
			function get_current()
			{
				return $this->current;
			}
		
			function get_address()
			{
				return $this->address;
			}
			
			function get_code()
			{
				return $this->code;
			}
			
			function get_col_order()
			{
				return $this->col_order;
			}			
			
			function get_name()
			{
				return $this->name;
			}	
			
			function get_filter()
			{				
				return $this->filter;
			}
	}
	
	$post = new post();
	
	// Database objects.
	$db			= NULL;	// Database.
	$query		= NULL;	// Query.
		
	// UK Space database objects.	
	$line_all	= NULL;	// Line object array.
	$line		= NULL;	// Individual line.
	
	$markup		= NULL; // Result markup.	
	
	// Initialize DB connection and query objects.	
	$db_conn_set = new ConnectConfig();
	$db_conn_set->set_name(DATABASE::NAME);
	
	$db = new Connect($db_conn_set);
	$query = new \dc\yukon\Database($db);	
	
	// Accounts (Inspectors)
	$_obj_data_list = new \data\Account();

	$query->set_sql('{call account_list_inspector()}');
	$query->query();		
	
	$query->get_line_params()->set_class_name('\data\Account');
	
	$_obj_data_list = new \data\Account();
	if($query->get_row_exists() === TRUE) $_obj_data_list = $query->get_line_object_list();
?>
    
	<optgroup label="Groups">                            
    	<option value="<?php echo \dc\yukon\DEFAULTS::NEW_ID; ?>" <?php if($post->get_current() == \dc\yukon\DEFAULTS::NEW_ID) echo ' selected ' ?>>All</option>
    </optgroup>
    <optgroup label="Inspectors">
<?php
		
	
	write($_obj_data_list, $post->get_current());
		
	
	// Output completed markup.
	echo $markup;	
?>