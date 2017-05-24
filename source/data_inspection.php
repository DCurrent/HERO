<?php

	require_once(__DIR__.'/data/Common.php');
	
	class class_common_inspection_data extends Common
	{	
		protected
			$room_code			= NULL,
			$room_code			= NULL,
			$building_code		= NULL,
			$building_name		= NULL,
			$status				= NULL,
			$status_label		= NULL,
			$inspection_type	= NULL,
			$inspection_type_label = NULL,
			$radiation_usage	= NULL;
		
		public function get_room_code()
		{
			return $this->room_code;
		}
		
		public function get_room_code()
		{
			return $this->room_code;
		}
		
		public function get_building_code()
		{
			return $this->building_code;
		}
		
		public function get_building_name()
		{
			return $this->building_name;
		}
		
		public function get_status()
		{
			return $this->status;
		}	
		
		public function get_status_label()
		{
			return $this->status_label;
		}
		
		public function get_inspection_type()
		{
			return $this->inspection_type;
		}
		
		public function get_inspection_type_label()
		{
			return $this->inspection_type_label;
		}
		
		public function get_radiation_usage()
		{
			return $this->radiation_usage();
		}
		
		// Mutators
		public function set_room_code($value)
		{
			$this->room_code = $value;
		}
					
		public function set_location($value)
		{
			$this->location = $value;
		}
		
		public function set_status($value)
		{
			$this->status = $value;
		}	
		
		public function set_inspection_type($value)
		{
			$this->inspection_type = $value;
		}
		
		public function set_inspection_type_label($value)
		{
			$this->inspection_type_label = $value;
		}
	}
	
	// tbl_inspection_autoclave_detail
	class class_inspection_autoclave_detail_data extends class_common_inspection_data
	{
		private
			$biowaste		= NULL,
			$maker			= NULL,
			$model			= NULL,
			$serial			= NULL,						
			$tag			= NULL;
		
		// Get and return an xml string for database use.
		public function xml()
		{
			// If there are no rows at all in the html form, we have no array.
			// We'll create the array with a single value that can never be used
			// as ID's in a database. this will cause the stored procedure to 
			// remove all sub rows to match the blank html form.
			if(is_array($this->id) == FALSE)
			{
				$this->id[0] = \dc\yukon\DEFAULTS::NEW_ID;
			}
			
			$result = '<root>';
						
			foreach($this->id as $key => $id)
			{	
				if(isset($this->biowaste[$key]) == FALSE) 
				{
					$this->biowaste[$key] = '1';
				}
										
				$result .= '<row id="'.$id.'">';				
				$result .= '<label>'.$this->label[$key].'</label>';
				$result .= '<details>'.htmlspecialchars($this->details[$key]).'</details>';
				$result .= '<biowaste>'.$this->biowaste[$key].'</biowaste>';
				$result .= '<maker>'.$this->maker[$key].'</maker>';
				$result .= '<model>'.$this->model[$key].'</model>';
				$result .= '<serial>'.$this->serial[$key].'</serial>';
				$result .= '<tag>'.$this->tag[$key].'</tag>';
				$result .= '</row>';									
			}
			
			$result .= '</root>';
			
			return $result;
		}
			
		public function get_biowaste()
		{
			return $this->biowaste;
		}
		
		public function get_maker()
		{
			return $this->maker;
		}
		
		public function get_model()
		{
			return $this->model;
		}
		
		public function get_serial()
		{
			return $this->serial;
		}
		
		public function get_tag()
		{
			return $this->tag;
		}
		
		
		// Mutators
		// --Override the mutators from parent class 
		// that we are populating with sub data.
		public function set_id($value){}		
		public function set_details($value){}		
		public function set_label($value){}
		
		public function set_sub_autoclave_detail_id($value)
		{
			$this->id = $value;
		}
		
		public function set_sub_autoclave_detail_label($value)
		{
			$this->label = $value;
		}
		
		public function set_sub_autoclave_detail_details($value)
		{
			$this->details = $value;
		}
		
		public function set_sub_autoclave_detail_biowaste($value)
		{
			$this->biowaste = $value;
		}
			
		public function set_sub_autoclave_detail_maker($value)
		{
			$this->maker = $value;
		}
		
		public function set_sub_autoclave_detail_model($value)
		{
			$this->model = $value;
		}
		
		public function set_sub_autoclave_detail_serial($value)
		{
			$this->serial = $value;
		}
		
		public function set_sub_autoclave_detail_tag($value)
		{
			$this->tag = $value;
		}
	}
	
	
	class class_inspection_visit_data extends Common
	{
		private
			$visit_type = NULL,
			$visit_by	= NULL;
			
		
		// Get and return an xml string for database use.
		public function xml()
		{
			// If there are no rows at all in the html form, we have no array.
			// We'll create the array with a single value that can never be used
			// as ID's in a database. this will cause the stored procedure to 
			// remove all sub rows to match the blank html form.
			if(is_array($this->id) == FALSE)
			{
				$this->id[0] = \dc\yukon\DEFAULTS::NEW_ID;
			}
			
			$result = '<root>';
						
			foreach($this->id as $key => $id)
			{
				// Can't send blank guids.
				$visit_by 	= $this->visit_by[$key];
				$visit_type	= $this->visit_type[$key];
					
				if($visit_by == NULL) 	$visit_by 	= \dc\yukon\DEFAULTS::NEW_ID;
				if($visit_type == NULL) $visit_type = \dc\yukon\DEFAULTS::NEW_ID;
				
				$result .= '<row id="'.$id.'">';				
				$result .= '<label>'.$this->label[$key].'</label>';
				$result .= '<details>'.htmlspecialchars($this->details[$key]).'</details>';
				$result .= '<visit_by>'.$visit_by.'</visit_by>';
				$result .= '<visit_type>'.$visit_type.'</visit_type>';
				$result .= '<time_recorded>'.$this->time_recorded[$key].'</time_recorded>';
				$result .= '</row>';									
			}
			
			$result .= '</root>';
			
			return $result;
		}
			
		public function get_visit_type()
		{
			return $this->visit_type;
		}
		
		public function get_visit_by()
		{
			return $this->visit_by;
		}
		
		// Mutators
		// --Override the mutators from parent class 
		// that we are populating with sub data.
		public function set_id($value){}		
		public function set_details($value){}		
		public function set_label($value){}
		public function set_time_recorded($value){}		
		
		public function set_sub_visit_id($value)
		{
			$this->id = $value;
		}
		
		public function set_sub_visit_type($value)
		{
			$this->visit_type = $value;
		}
		
		public function set_sub_visit_by($value)
		{
			$this->visit_by = $value;
		}
		
		public function set_sub_visit_time_recorded($value)
		{
			$this->time_recorded = $value;
		}
	}
	
	class class_inspection_party_data extends Common
	{
		private
			$party	= NULL;
		
		// Get and return an xml string for database use.
		public function xml()
		{
			// If there are no rows at all in the html form, we have no array.
			// We'll create the array with a single value that can never be used
			// as ID's in a database. this will cause the stored procedure to 
			// remove all sub rows to match the blank html form.
			if(is_array($this->id) == FALSE)
			{
				$this->id[0] = \dc\yukon\DEFAULTS::NEW_ID;
			}
			
			$result = '<root>';
						
			foreach($this->id as $key => $id)
			{	
				$party = $this->party[$key];
				
				if($party == NULL) $party = \dc\yukon\DEFAULTS::NEW_ID;
										
				$result .= '<row id="'.$id.'">';				
				$result .= '<label>'.$this->label[$key].'</label>';
				$result .= '<details>'.htmlspecialchars($this->details[$key]).'</details>';
				$result .= '<party>'.$party.'</party>';
				$result .= '</row>';									
			}
			
			$result .= '</root>';
			
			return $result;
		}
		
		// Accessors
		public function get_party()
		{
			return $this->party;
		}
		
		// Mutators
		// --Override the mutators from parent class 
		// that we are populating with sub data.
		public function set_id($value){}		
		public function set_details($value){}		
		public function set_label($value){}		
		
		public function set_sub_party_id($value)
		{
			$this->id = $value;
		}
		
		public function set_sub_party_party($value)
		{
			$this->party = $value;
		}
	}
	
	class class_inspection_saa_detail_data extends class_common_inspection_data
	{
		private
			$category 		= NULL,
			$correction	= NULL;
		
		// Get and return an xml string for database use.
		public function xml()
		{
			// If there are no rows at all in the html form, we have no array.
			// We'll create the array with a single value that can never be used
			// as ID's in a database. this will cause the stored procedure to 
			// remove all sub rows to match the blank html form.
			if(is_array($this->id) == TRUE)
			{
				//$this->id[0] = \dc\yukon\DEFAULTS::NEW_ID;
			
			
				$result = '<root>';
							
				foreach($this->id as $key => $id)
				{
					// Can't send blank guids.
					$category 		= $this->category[$key];
					$correction		= $this->correction[$key];
						
					if($category == NULL) 	$category 		= \dc\yukon\DEFAULTS::NEW_ID;
					if($correction == NULL) $correction = \dc\yukon\DEFAULTS::NEW_ID;
					
					$result .= '<row id="'.$id.'">';				
					$result .= '<label>'.$this->label[$key].'</label>';
					$result .= '<details>'.htmlspecialchars($this->details[$key]).'</details>';
					//$result .= '<category>'.$category.'</category>';
					$result .= '<correction>'.$correction.'</correction>';
					$result .= '</row>';									
				}
				
				$result .= '</root>';
			}
			return $result;
		}
			
		public function get_category()
		{
			return $this->category;
		}
		
		public function get_correction()
		{
			return $this->correction;
		}
		
		
		// Mutators
		// --Override the mutators from parent class 
		// that we are populating with sub data.
		public function set_id($value){}		
		public function set_details($value){}		
		public function set_label($value){}
		
		public function set_sub_saa_detail_id($value)
		{
			$this->id = $value;
		}
		
		public function set_sub_saa_detail_label($value)
		{
			$this->label = $value;
		}
		
		public function set_sub_saa_detail_details($value)
		{
			$this->details = $value;
		}
		
		/*
		public function set_sub_saa_detail_category($value)
		{
			$this->category = $value;
		}
		*/
			
		public function set_sub_saa_detail_correction($value)
		{
			$this->correction = $value;
		}
	}
	
	class class_inspection_saa_correction_data extends Common
	{
		private
			$area_label	= NULL,
			$order	= NULL;
			
		public function get_category_label()
		{
			return $this->category_label;
		}
		
		public function get_order()
		{
			return $this->order;
		}
	}
	
	class class_audit_question_data extends Common
	{
		private
			$finding 			= NULL,
			$corrective_action	= NULL;
			
		// Accessors
		public function get_corrective_action()
		{
			return $this->corrective_action;
		}
		
		public function get_finding()
		{
			return $this->finding;
		}
		
		// Mutators
		public function set_corrective_action($value)
		{
			$this->corrective_action = $value;
		}
		
		public function set_finding($value)
		{
			$this->finding = $value;
		}		
	}
	
	class class_audit_question_category_data extends Common
	{
		// Get and return an xml string for database use.
		public function xml()
		{
			$result = '<root>';
			
			if(is_array($this->id) === TRUE)			
			{
				foreach($this->id as $key => $id)
				{								
					$result .= '<row id="'.$id.'">';
					$result .= '<item_id>'.$this->item_id[$key].'</item_id>';
					$result .= '</row>';									
				}			
			}
			
			$result .= '</root>';
			
			return $result;
		}
		
		// Mutators
		// "sub" mutators prevent multiple instances of the same
		// data member name from different classes on a form
		// from interfering with each other.
		public function set_item_id($value)
		{
		}
		
		public function set_id($value)
		{
		}
		
		public function set_sub_category_id($value)
		{			
			$this->id = $value;			
		}
							
		public function set_sub_category_item_id($value)
		{
			$this->item_id = $value;			
		}
	}
	
	class class_audit_question_inclusion_data extends Common
	{
		// Get and return an xml string for database use.
		public function xml()
		{
			$result = '<root>';
			
			if(is_array($this->id) === TRUE)			
			{
				foreach($this->id as $key => $id)
				{								
					$result .= '<row id="'.$id.'">';
					$result .= '<item_id>'.$this->item_id[$key].'</item_id>';
					$result .= '</row>';									
				}			
			}
			
			$result .= '</root>';
			
			return $result;
		}
		
		// Mutators
		// "sub" mutators prevent multiple instances of the same
		// data member name from different classes on a form
		// from interfering with each other.
		public function set_item_id($value)
		{
		}
		
		public function set_id($value)
		{
		}
		
		public function set_sub_inclusion_id($value)
		{			
			$this->id = $value;			
		}
							
		public function set_sub_inclusion_item_id($value)
		{
			$this->item_id = $value;			
		}
	}
	
	class class_audit_question_rating_data extends Common
	{
		// Get and return an xml string for database use.
		public function xml()
		{
			$result = '<root>';
			
			if(is_array($this->id) === TRUE)			
			{
				foreach($this->id as $key => $id)
				{								
					$result .= '<row id="'.$id.'">';
					$result .= '<item_id>'.$this->item_id[$key].'</item_id>';
					$result .= '</row>';									
				}			
			}
			
			$result .= '</root>';
			
			return $result;
		}
		
		// Mutators
		// "sub" mutators prevent multiple instances of the same
		// data member name from different classes on a form
		// from interfering with each other.
		public function set_item_id($value)
		{
		}
		
		public function set_id($value)
		{
		}
		
		public function set_sub_rating_id($value)
		{			
			$this->id = $value;			
		}
							
		public function set_sub_rating_item_id($value)
		{
			$this->item_id = $value;			
		}
	}
	
	class class_audit_question_reference_data extends Common
	{
		// Get and return an xml string for database use.
		public function xml()
		{
			$result = '<root>';
			
			if(is_array($this->id) === TRUE)			
			{
				foreach($this->id as $key => $id)
				{								
					$result .= '<row id="'.$id.'">';
					$result .= '<label>'.$this->label[$key].'</label>';
					$result .= '<details>'.htmlspecialchars($this->details[$key]).'</details>';
					$result .= '</row>';									
				}			
			}
			
			$result .= '</root>';
			
			return $result;
		}
		
		// Mutators
		// "sub" mutators prevent multiple instances of the same
		// data member name from different classes on a form
		// from interfering with each other.
		public function set_id($value)
		{
		}
		
		public function set_details($value){}
		public function set_label($value){}
		
		public function set_sub_reference_id($value)
		{			
			$this->id = $value;			
		}
							
		public function set_sub_reference_label($value)
		{
			$this->label = $value;			
		}
		
		public function set_sub_reference_details($value)
		{
			$this->details = $value;			
		}
	}
?>