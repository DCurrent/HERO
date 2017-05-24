<?php

	namespace data;
	
	interface iArea
	{
		// Accessors. 
		function get_building_code();
		function get_building_name();
		//function get_code();
		function get_department();
		function get_floor();
		function get_room_code();
		function get_room_id();	
		function get_use_code();
		function get_use_definition();
		function get_use_description_short();
		function get_radiation_usage();	
		
		function get_laser_usage();
		function get_x_ray_usage();
		function get_chemical_operations_class();
		function get_chemical_lab_class();
		function get_ibc_protocol();
		function get_biosafety_level();
		function get_nfpa45_lab_unit();
		function get_hazardous_waste_generated();
		
		// Mutators
		function set_building_code($value);
		function set_building_name($value);
		//function set_code($value);
		function set_department($value);
		function set_floor($value);
		function set_room_code($value);
		function set_use_code($value);
		function set_use_definition($value);
		function set_use_description_short($value);
		function set_radiation_usage($value);	
		
		function set_laser_usage($value);
		function set_x_ray_usage($value);
		function set_chemical_operations_class($value);
		function set_chemical_lab_class($value);
		function set_ibc_protocol($value);
		function set_biosafety_level($value);
		function set_nfpa45_lab_unit($value);
		function set_hazardous_waste_generated($value);
	}
	
	class Area extends Common implements iArea
	{
		protected			
			$biosafety_level		= NULL,
			$bio_agent				= NULL,
			$building_code			= NULL,
			$building_name			= NULL,
			$chemical_lab_class		= NULL,
			$chemical_operations_class = NULL,	
			$department				= NULL,		
			$ibc_protocal			= NULL,
			$floor					= NULL,
			$hazardous_waste_generated		= NULL,
			$laser_usage			= NULL,
			$nfpa45_lab_unit		= NULL,
			$room_code				= NULL,
			$room_id				= NULL,
			$radiation_usage		= NULL,						
			$use_code				= NULL,
			$use_definition			= NULL,
			$use_description_short	= NULL,
			$x_ray_usage			= NULL;
			
			
		// Accessors
		public 
			function get_building_code()
			{
				return $this->building_code;
			}
			
			function get_building_name()
			{
				return $this->building_name;
			}
			
			function get_department()
			{
				return $this->department;
			}
			
			function get_floor()
			{
				return $this->floor;
			}
			
			function get_room_code()
			{
				return $this->room_code;
			}
			
			function get_room_id()
			{
				return $this->room_id;
			}
			
			function get_use_code()
			{
				return $this->use_code;
			}
			
			function get_use_definition()
			{
				return $this->use_definition;
			}
			
			function get_use_description_short()
			{
				return $this->use_description_short;
			}
			
			function get_radiation_usage()
			{
				return $this->radiation_usage;
			}
		
			function get_laser_usage()
			{
				return $this->laser_usage;
			}
			
			function get_x_ray_usage()
			{
				return $this->x_ray_usage;
			}
			
			function get_chemical_operations_class()
			{
				return $this->chemical_operations_class;
			}
			
			function get_chemical_lab_class()
			{
				return $this->chemical_lab_class;
			}
			
			function get_ibc_protocol()
			{
				return $this->ibc_protocal;
			}
			
			function get_biosafety_level()
			{
				return $this->biosafety_level;
			}
			
			function get_nfpa45_lab_unit()
			{
				return $this->nfpa45_lab_unit;
			}
		
			function get_hazardous_waste_generated()
			{
				return $this->hazardous_waste_generated;
			}
		
		// Mutators
		public 
			function set_building_code($value)
			{
				$this->building_code = $value;
			}
			
			function set_building_name($value)
			{
				$this->building_name = $value;
			}
		
			function set_department($value)
			{
				$this->department = $value;
			}
			
			function set_floor($value)
			{
				$this->floor = $value;
			}
			
			function set_room_code($value)
			{
				$this->room_code = $value;
			}
			
			function set_use_code($value)
			{
				$this->use_code = $value;
			}
			
			function set_use_definition($value)
			{
				$this->use_definition = $value;
			}
			
			function set_use_description_short($value)
			{
				$this->use_description_short = $value;
			}
			
			function set_radiation_usage($value)
			{							
				$this->radiation_usage = $value;
			}
			
			
			function set_laser_usage($value)
			{
				$this->laser_usage = $value;
			}
			
			function set_x_ray_usage($value)
			{
				$this->x_ray_usage = $value;
			}
			
			function set_chemical_operations_class($value)
			{
				$this->chemical_operations_class = $value;
			}
			
			function set_chemical_lab_class($value)
			{
				$this->chemical_lab_class = $value;
			}
			
			function set_ibc_protocol($value)
			{
				$this->ibc_protocal = $value;
			}
			
			function set_biosafety_level($value)
			{
				$this->biosafety_level = $value;
			}
			
			function set_nfpa45_lab_unit($value)
			{
				$this->nfpa45_lab_unit = $value;
			}
			
			function set_hazardous_waste_generated($value)
			{
				$this->hazardous_waste_generated = $value;
			}
			
	}	
?>