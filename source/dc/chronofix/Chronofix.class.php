<?php

namespace dc\chronofix;

interface iChronofix
{
	function get_settings();								// Get current settings object.
	function get_time();									// Get date/time value.
	function is_valid($value);								// Evaluates current date time string against current format. True of valid, false otherwise.
	function sanitize();									// Validate current date/time and replace it with string in current format.
	function set_settings(Config $value);		// Set the settings object.
	function set_time($value);								// Set the date/time value.
}

class Chronofix implements iChronofix
{	
	protected 
		$time		= NULL,	// Time/date value to manipulate.
		$settings	= NULL;	// Settings object.
	
	public function __construct(Config $settings = NULL)
	{		
		// Set settings object. Before doing so, make
		// sure settings argument is populated. If not
		// we need to establish a new settings object.
		if(!$settings)
		{
			$settings = new Config();	
		}
		
		$this->set_settings($settings);				
	}
	
	// Accessors
	public function get_settings()
	{
		return $this->settings;
	}
	
	public function get_time()
	{
		return $this->time;
	}
	
	// Mutators
	public function set_settings(Config $value)
	{
		$this->settings = $value;
	}
	
	public function set_time($value)
	{
		$this->time = $value;
	}
	
	// Remove special chars and validate
	// a date. Returns NULL on fail.
	public function sanitize()
	{
		$result 	= NULL;	// End result.
		$timestamp	= NULL;	// Unix timestamp.
		$time_str	= NULL;	// Time string.
		$time		= NULL;	// Time value.
		
		$time = $this->time;
		
		// Was an argument passed?
		if($time)
		{
			// Convert string date to 
			// Unix timestamp.
			$timestamp = strtotime($time);
			
			// Convert timestamp to specfic date format.
			$time_str = date($this->settings->get_format(), $timestamp);
			
			// Now validate the resulting date format. If
			// it is a valid date, pass on as a result.
			if($this->is_valid($time_str))
			{				
				$obj_date = new DateTime($time_str);
			
				$result = $obj_date->format($this->settings->get_format());
			}
		}					
		
		// Set current time value to result.
		$this->time = $result;
		
		return $result;
	}
		
	// Return TRUE if time string argument is a valid
	// time of preset format.
	public function is_valid($value)
	{	
		$result = FALSE;
	
		// Create a date object from date
		// string based on format.						
		$date = DateTime::createFromFormat($this->settings->get_format(), $value);
		
		// Date object valid?
		if($date)
		{
			// Object date output matches
			// date argument?
			if($date->format($this->settings->get_format()) == $value)
			{
				$result = TRUE;			
			}
		}
		
		// Return final result.
		return $result;
	}
}

?>