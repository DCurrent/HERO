<?php
	
	namespace data;

	interface iObservationSource
	{
		// Accessors.
		function get_item();
		function get_observation();
		function get_solution();
		function get_status();
		function get_result();
				
		// Mutators.
		function set_item($value);
		function set_observation($value);
		function set_solution($value);
		function set_status($value);
		function set_result($value);
	}	
	
	class ObservationSource extends Common implements iObservationSource
	{
		protected
			$item			= NULL,
			$observation 	= NULL,
			$result			= NULL,
			$solution		= NULL,
			$status			= NULL;
		
		public function xml()
		{
			$result 			= NULL;			
			$observation_result = NULL;
			
			if(is_array($this->item))
			{				
				$result = '<root>';
							
				foreach($this->item as $key => $item)
				{
					$observation_result = NULL;
					
					// HTML radio buttons cannot be part of the 
					// same array like other inputs since the name is what
					// locks a group of radio buttons together. 
					// That means we have to identify each set of radio
					// buttons as a unique request and break them down
					// here.
					if(isset($_REQUEST['result_'.$item]))
					{
						$observation_result = $_REQUEST['result_'.$item];
					}
					
					$result .= '<row item="'.$item.'">';				
					$result .= '<result>'.$observation_result.'</result>';					
					
					$result .= '</row>';									
				}
				
				$result .= '</root>';
			}
			
			return $result;
		}
		
		public function get_observation()
		{
			return $this->observation;
		}
		
		public function get_result()
		{
			return $this->result;
		}
		
		public function get_solution()
		{
			return $this->solution;
		}
		
		public function get_status()
		{
			return $this->status;
		}
	
		// Mutators		
		public function set_observation($value)
		{
			$this->observation = $value;
		}
		
		public function set_result($value)
		{
			$this->result = $value;
		}

		public function set_solution($value)
		{
			$this->solution = $value;
		}
		
		public function set_status($value)
		{
			$this->status = $value;
		}
	}
?>
