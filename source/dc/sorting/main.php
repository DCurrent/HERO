
<?php	
	require_once(__DIR__.'/../url_query/main.php');
		
	abstract class SORTING_URL_KEY
	{
		// If these constant values are changed, make sure to update
		// the mutator methods as well.
		const
			ORDER	= 'order',
			FILTER	= 'field';
	}
	
	abstract class SORTING_ORDER_MARKUP
	{
		const 		
			ASCENDING	= '<span class="glyphicon glyphicon glyphicon-sort-by-alphabet"></span>',
			DECENDING	= '<span class="glyphicon glyphicon glyphicon-sort-by-alphabet-alt"></span>',
			NONE 		= '<span class="glyphicon glyphicon glyphicon-sort"></span>';
	}
	
	abstract class SORTING_ORDER_TYPE
	{
		const 
			ASCENDING 	= 0,
			DECENDING	= 1;			
	}	
	
	
	class class_sort_control
	{
		private
			$sort_field		= NULL,		
			$sort_order 	= NULL,
			$sorting_markup_list = array(),
			$url_query 		= NULL,
			$url_base		= NULL,
			$url_sort		= NULL;	// Final url to use in sorting control link.
		
		public function __construct()
		{			
			$this->sorting_markup_list[SORTING_ORDER_TYPE::ASCENDING] 	= SORTING_ORDER_MARKUP::ASCENDING;
			$this->sorting_markup_list[SORTING_ORDER_TYPE::DECENDING] 	= SORTING_ORDER_MARKUP::DECENDING;													
		}
		
		// Accessors
		public function get_sort_field()
		{
			return $this->sort_field;
		}
		
		public function get_sort_order()
		{
			return $this->sort_order;
		}
		
		// Mutators
		public function set_sort_field($value)
		{
			$this->sort_field = $value;
		}
		
		public function set_sort_order($value)
		{
			$this->sort_order = $value;
		}
		
		// Request mutators
		private function set_field($value)
		{
			$this->sort_field = $value;			
		}
		
		private function set_order($value)
		{
			$this->sort_order = $value;
		}
		
		// Populate members from $_REQUEST.
		public function populate_from_request()
		{		
			// Interate through each class method.
			foreach(get_class_methods($this) as $method) 
			{		
				$key = str_replace('set_', '', $method);
							
				// If there is a request var with key matching
				// current method name, then the current method 
				// is a set mutator for this request var. Run 
				// it (the set method) with the request var. 
				if(isset($_GET[$key]))
				{					
					$this->$method($_GET[$key]);					
				}
			}
		}
		
		public function sorting_markup($sort_field)
		{
			$result = NULL;
			
			if($this->sort_field == $sort_field)
			{
				switch($this->sort_order)
				{
					case SORTING_ORDER_TYPE::ASCENDING:
						$result = SORTING_ORDER_MARKUP::ASCENDING;
						break;
					
					case SORTING_ORDER_TYPE::DECENDING:
						$result = SORTING_ORDER_MARKUP::DECENDING;
						break;
					
					default:
						$result = SORTING_ORDER_MARKUP::NONE;
				}				
			}
			else
			{
				$result = SORTING_ORDER_MARKUP::NONE;
			}
			
			return $result;
		}
		
		public function sort_url($sort_field)
		{
			$url_query = new \dc\url\URLFix();
			
			// Add sorting field data to url.
			$url_query->set_data(SORTING_URL_KEY::FILTER, $sort_field);
			
			// Is $sort_field the current sorting field in use?
			if($this->sort_field == $sort_field)
			{				
				// Add sorting order data to url. It should always be opposite 
				// of current order in use.
				if($this->sort_order == SORTING_ORDER_TYPE::ASCENDING)
				{
					$url_query->set_data(SORTING_URL_KEY::ORDER, SORTING_ORDER_TYPE::DECENDING);
				}
				else
				{
					$url_query->set_data(SORTING_URL_KEY::ORDER, SORTING_ORDER_TYPE::ASCENDING);
				}
			}
			else
			{
				$url_query->set_data(SORTING_URL_KEY::ORDER, SORTING_ORDER_TYPE::ASCENDING);
			}					 	
			
			// Combine the base url with encoded url from our
			// sorting data to create a ready to use url
			// for sort control link.
			$result = $this->url_base.$url_query->return_url_encoded(); 			
			
			// Return result.
			return $result;
		}					
	}
 ?>