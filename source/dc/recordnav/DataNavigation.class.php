<?php

	namespace dc\recordnav;
	
	require_once('config.php');
	
	class DataNavigation
	{
		protected 
			$fk_id			= NULL,
			$id				= NULL,
			$id_key			= NULL,
			$id_first		= NULL,
			$id_last		= NULL,
			$id_next		= NULL,
			$id_previous	= NULL;
		
		public function populate_from_request()
		{
			// Interate through each class variable.
			foreach($this as $key => $value) 
			{			
				// If we can find a matching a post var with key matching
				// key of current object var, set object var to the post value. 
				if(isset($_REQUEST[$key]))
				{
					// Add 'set_' prefix so member name is now a mutator method name.
					$method = 'set_'.$key;
					
					// If a mutator method by the current name exists, run it and
					// pass current request value. 
					if(method_exists($this, $method)=== TRUE)
					{						
						$this->$method($_REQUEST[$key]);						
					}
				}
			}
		}
		
		// Accessors
		public function get_fk_id()
		{
			return $this->fk_id;
		}
		
		public function get_id()
		{
			return $this->id;
		}
		
		public function get_id_key()
		{
			return $this->id_key;
		}
		
		public function get_id_first()
		{
			return $this->id_first;
		}
		
		public function get_id_last()
		{
			return $this->id_last;
		}
		
		public function get_id_next()
		{
			return $this->id_next;
		}
		
		public function get_id_previous()
		{
			return $this->id_previous;
		}
		
		// mutators.
		public function set_fk_id($value)
		{
			$this->fk_id = $value;
		}
		
		public function set_id($value)
		{
			$this->id = $value;				
		}
		
		public function set_id_key($value)
		{
			$this->id_key = $value;				
		}
		
		public function set_id_first($value)
		{
			$this->id_first = $value;
		}
		
		public function set_id_last($value)
		{
			$this->id_last = $value;
		}
		
		public function set_id_next($value)
		{
			$this->id_next = $value;
		}
		
		public function set_id_previous($value)
		{
			$this->id_previous = $value;
		}
	}

?>