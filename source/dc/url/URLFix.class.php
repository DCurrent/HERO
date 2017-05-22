<?php

	namespace dc\url;
	
	final class URLFix
	{
		private 
			$data 		= NULL,
			$url_base	= NULL;
		
	
		function __construct($start = NULL) 
		{			
			if ($start === NULL)
			{
				$this->data = $_GET;
			}
			elseif (!is_array($start))
			{
				throw new InvalidArgumentException();				 
			}
			
			// Default base url to self.
			$this->url_base = $_SERVER['PHP_SELF'];
		}
	
		public function isset_data($key) 
		{
			return isset($this->data[$key]);
		}
	
		public function unset_data($key) 
		{
			unset($this->data[$key]);
		}
	
		public function get_data($key) 
		{
			return isset($this->data[$key]) ? $this->data[$key] : "";
		}
	
		public function set_url_base($value)
		{
			$this->url_base = $value;
		}
	
		public function set_data($key, $value) 
		{
			$this->data[$key] = $value;
		}
	
		public function return_url() 
		{
			return $this->getStringInternal(FALSE);
		}
	
		public function return_url_encoded() 
		{
			return $this->getStringInternal(TRUE);
		}		
	
		private function getStringInternal($HTML = FALSE) 
		{
			if (empty($this->data))
			{
				return '';
			}
			
			$result = '?';  
			      
			foreach ($this->data as $key => $value) 
			{
				if (!is_array($value)) 
				{
					if ($value === '')
					{
						$result .= urlencode($key).'&';
					}
					else
					{
						$result .= urlencode($key).'='.urlencode($value).'&';
					}
				}
				else
				{
					foreach ($value as $value_element) 
					{
						$result .= urlencode($key).'[]='.urlencode($value_element).'&';
					}
				}
			}
	
			// Clean query and add base URL.
			$result = $this->url_base.substr($result, 0, -1);
			
			// Replace special characters with HTML encoding?
			if ($HTML === TRUE)
			{
				$result = htmlspecialchars($result);
			}
			
			return $result;
		}
	}
?>