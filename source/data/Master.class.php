<?php
	
	namespace data;
	
	// Master data. Contains data from master table
	// that is used to audit and control all
	// other data tables and enable versioning.
	interface iMaster
	{
		// Accessors
		function get_active();
		function get_create_by();
		function get_create_host();
		function get_create_etime();
		function get_create_time();
		function get_fk_id();
		function get_id();
		function get_id_key();
		function get_update_by();
		function get_update_host();
		function get_update_etime();
		function get_update_time();	
		
		// Mutators	
		function set_active($value);
		function set_create_by($value);
		function set_create_host($value);
		function set_create_etime($value);
		function set_create_time($value);
		function set_fk_id($value);
		function set_id($value);
		function set_id_key($value);
		function set_update_by($value);
		function set_update_host($value);
		function set_update_etime($value);
		function set_update_time($value);	
	}

	// See interface for details
	class Master implements iMaster
	{
		protected
			$active			= NULL,	// TRUE = Flags a version of a record as being the active and in use - only one may be active, will usually be the newest.
			$create_by		= NULL,	// Account active when record was initially created.
			$create_host	= NULL,	// Host active when version was initially created. Usually an IP address.
			$create_etime	= NULL,	// Elapsed time in seconds since initial creation.
			$create_time	= NULL,	// Time version was initially created.
			$fk_id			= NULL,	// Foreign key for linking one to many record relationships.
			$id				= NULL,	// Record key. All versions of a given record share one key.
			$id_key			= NULL,	// Primary key - uniquely identifies every entry in table.
			$update_by		= NULL,	// Account active when version was last modified.
			$update_host	= NULL,	// Host active when version was ilast modified. Usually an IP address.
			$update_etime	= NULL,	// Elapsed time in seconds since last modification.
			$update_time	= NULL;	// Time version was last modified.
						
		// Accessors
		public function get_active()
		{
			return $this->active;
		} 
		
		public function get_create_by()
		{
			return $this->create_by;
		}
		
		public function get_create_host()
		{
			return $this->create_host;
		}
		
		public function get_create_etime()
		{
			return $this->create_etime;
		}
		
		public function get_create_time()
		{
			return $this->create_time;
		}
		
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
		
		public function get_update_by()
		{
			return $this->update_by;
		}
		
		public function get_update_host()
		{
			return $this->update_host;
		}
		
		public function get_update_etime()
		{
			return $this->update_etime;
		}
		
		public function get_update_time()
		{
			return $this->update_time;
		}
		
		// Mutators
		public function set_active($value)
		{
			$this->active = $value;
		} 
		
		public function set_create_by($value)
		{
			$this->create_by = $value;
		}
		
		public function set_create_host($value)
		{
			$this->create_host = $value;
		}
		
		public function set_create_etime($value)
		{
			$this->create_etime = $value;
		}
		
		public function set_create_time($value)
		{
			$this->create_time = $value;
		}
		
		public function set_fk_id($value)
		{
			// Ensure empty values are NULL, not ''.
			if(!$value)
			{
				$value = NULL;
			}
			
			$this->fk_id = $value;			
		}
		
		public function set_id($value)
		{
			// Ensure empty values are NULL, not ''.
			if(!$value)
			{
				$value = NULL;
			}
				
			$this->id = $value;
			
		}
		
		public function set_id_key($value)
		{
			// Ensure empty values are NULL, not ''.
			if(!$value)
			{
				$value = NULL;
			}
			
			$this->id_key = $value;			
		}
		
		public function set_update_by($value)
		{
			$this->update_by = $value;
		}
		
		public function set_update_host($value)
		{
			$this->update_host = $value;
		}
		
		public function set_update_etime($value)
		{
			$this->update_etime = $value;
		}
		
		public function set_update_time($value)
		{
			$this->update_time = $value;
		}
	}
?>
