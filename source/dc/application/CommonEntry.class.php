<?php

	namespace dc\application;
	
	interface iCommonEntry
	{
		// Accessors
		function get_description();
		function get_file_name();
		function get_id_form();
		function get_main_sql_name();
		function get_main_object_name();
		function get_slug();
		function get_title();
		
		// Mutators
		function set_description($value);
		function set_file_name($value);
		function set_id_form($value);
		function set_main_sql_name($value);
		function set_main_object_name($value);
		function set_slug($value);
		function set_title($value);	
	}
	
	class CommonEntry extends \data\Common implements iCommonEntry
	{
		protected
			$create_time		= NULL,
			$description		= NULL,
			$file_name			= NULL,		// If provided, redirect here instead of the common form.
			$id_form			= NULL,		// Same as ID - used to avoid name conflict with record ID in a form's URL query string.
			$main_sql_name		= NULL,		// SQL (usually a stored proc) that form will call for its primary record sets.
			$main_object_name	= NULL,		// The data object form uses for its primary record sets.		
			$slug				= NULL,
			$title				= NULL;		// Title the form will display.
		
		public function __construct()
		{			
		}
		
		// Accessors
		public function get_description()
		{
			return $this->description;
		}
		
		public function get_file_name()
		{
			return $this->file_name;
		}
		
		public function get_id_form()
		{
			return $this->id_form;
		}
		
		public function get_main_sql_name()
		{
			return $this->main_sql_name;
		}
		
		public function get_main_object_name()
		{
			return $this->main_object_name;
		}
		
		public function get_slug()
		{
			return $this->slug;
		}
		
		public function get_title()
		{
			return $this->title;
		}
		
		// Mutators			
		public function set_description($value)
		{
			$this->description = $value;	
		}
		
		public function set_file_name($value)
		{
			$this->file_name = $value;	
		}
		
		public function set_id_form($value)
		{
			$this->id_form = $value;
		}
		
		public function set_main_sql_name($value)
		{
			$this->main_sql_name = $value;
		}
		
		public function set_main_object_name($value)
		{
			$this->main_object_name = $value;
		}
		
		public function set_slug($value)
		{
			$this->slug = $value;
		}
		
		public function set_title($value)
		{
			$this->title = $value;
		}
	}	
?>