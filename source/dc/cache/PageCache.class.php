<?php

	namespace dc\cache;

	interface iPageCache
	{
		// Accessors
		function get_markup();
		function get_time_start();
		
		// Mutators
		function set_markup($value);
		
		// Operations
		function clean_cache();		
		function markup_and_flush();		// Record cache into object member and flush the cache contents.		
		function markup_from_cache();	// Turn off cache and output markup to screen.
		function output_markup();
		function time_elapsed();
	}
	
	class PageCache implements iPageCache
	{
		private 
			$markup = NULL,
			$time_start	= NULL;
		
		public function __construct()
		{
			$this->time_start = microtime(TRUE);
			ob_start();			
		}
		
		public function __destruct()
		{
			// Bad idea - there might be stacked
			// caches. 
			//$this->clean_cache();
		}
		
		public function get_time_start()
		{
			return $this->time_start;
		}
		
		public function time_elapsed()
		{
			return (number_format(microtime(TRUE) - $this->time_start, 5));
		}
		
		public function markup_from_cache()
		{
			$this->markup = ob_get_contents();			
			return $this->markup;
		}
		
		// Record cache into object member and flush
		// the cache contents.
		public function markup_and_flush()
		{
			$this->markup = ob_get_contents();
			
			$this->clean_cache();
						
			return $this->markup;
		}
		
		// Turn off cache and output markup to screen.
		public function output_markup()
		{			
			echo $this->markup;
			
		}
		
		public function get_markup()
		{
			return $this->markup;
		}
		
		public function set_markup($value)
		{
			$this->markup = $value;
		}
		
		public function clean_cache()
		{
			ob_end_clean();
		}
	}
?>
