<?php
	
	namespace data;

	interface iObservationSource
	{
		// Accessors.
		function get_observation();
		function get_solution();
				
		// Mutators.
		function set_observation($value);
		function set_solution($value);		
	}	
	
	class ObservationSource extends Common implements iObservationSource
	{
		protected
			$observation 	= NULL,
			$solution	= NULL;
			
		public function get_observation()
		{
			return $this->observation;
		}
		
		public function get_solution()
		{
			return $this->solution;
		}
	
		// Mutators		
		public function set_observation($value)
		{
			$this->observation = $value;
		}

		public function set_solution($value)
		{
			$this->solution = $value;
		}
	}
?>
