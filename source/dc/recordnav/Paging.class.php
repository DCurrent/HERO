<?php

	namespace dc\recordnav;
	
	require_once('config.php');

	// Paging handler.
	interface iPaging
	{
		// Accessors
		function get_markup();
		function get_page_current();
		function get_page_last();
		function get_row_count();
		function get_row_max();	
		
		// Mutators
		function set_markup($value);		
		function set_row_count_total($value);			
		function set_row_max($value);
		function set_page($value);
		function set_page_last($value);
		
		// Operations.
		function generate_paging_markup();
		function populate_from_request();				
	}
	
	class Paging implements iPaging
	{
		const 
			ROW_MAX					= 25,
			REQUEST_KEY_PAGE_NUMBER	= 'page'; // If this is changed, make sure to change the member name and mutator.
					
		private			
			$markup				= NULL,		
			$page_last			= NULL,			
			$page_current		= NULL,
			$row_count_total	= NULL,	// Row count without paging.
			$row_max			= NULL;
				
		public function __construct()
		{			
			$this->populate_from_request();
			
			$this->row_max = self::ROW_MAX;
			
			// Make sure the page number request is a positive numeric value.
			if (!is_numeric($this->page_current) || $this->page_current < 1)
			{ 
				$this->page_current = 1; 
			}						
		}				
		
		// Accessors
		public function get_markup()
		{
			return $this->markup;
		}
		
		public function get_row_max()
		{
			return $this->row_max;
		}
		
		public function get_row_count()
		{
			return $this->row_count_total;
		}
		
		public function get_page_last()
		{
			return $this->page_last;
		}	
		
		public function get_page_current()
		{
			return $this->page_current;
		}
		
		// Mutators
		public function set_markup($value)
		{
			$this->markup = $value;
		}
		
		public function set_row_count_total($value)
		{
			$this->row_count_total = $value;
		}
		
		public function set_row_max($value)
		{
			$this->row_max = $value;
		}
		
		public function set_page_last($value)
		{
			$this->page_last = $value;
		}
		
		public function set_page($value)
		{
			$this->page_current = $value;
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
				
		public function generate_paging_markup()
		{			
			$url_query	= new \dc\url\URLFix;
		
			// Start caching page contents.
			ob_start();
			?>
            
            <div class="btn-group btn-group-justified">
            
			<?php
			// First check to see if we are on page one. If we are then we don't need a link to the previous page or the first page 
			// so we build dummy buttons. If we aren't (on first page) then we generate links to the first page, and to the previous page.
			if ($this->page_current == 1) 
			{
			?>
           		<div class="btn-group"> 
					<button 
						type		="button" 
						name		="paging_first"
						id			="paging_first"
						class		="btn btn-primary btn-sm disabled"  
						title		="Go to first page."
						><span class="glyphicon glyphicon-fast-backward"></span></button>
				</div>
                
                <div class="btn-group"> 
					<button 
						type		="button" 
						name		="paging_prev"
						id			="paging_prev"
						class		="btn btn-primary btn-sm disabled" 
						title		="Go to previous page."
						><span class="glyphicon glyphicon-backward"></span></button>
				</div>
			<?php
            } 
			else 
			{				
				// Build URL query.		
				$url_query->set_data(self::REQUEST_KEY_PAGE_NUMBER, 1);			
			?>
            	<a                   
                    class		="btn btn-primary btn-sm" 
                    title		="Go to first page."
                    href="<?php echo $url_query->return_url_encoded(); ?>"><span class="glyphicon glyphicon-fast-backward"></span></a>
            <?php
				// Build URL query.		
				$url_query->set_data(self::REQUEST_KEY_PAGE_NUMBER, $this->page_current-1);
			?>                
               	<a 
                   	class		="btn btn-primary btn-sm" 
                    title		="Go to previous page." 
                    href="<?php echo $url_query->return_url_encoded(); ?>"><span class="glyphicon glyphicon-backward"></span></a>
			<?php
            } 
						
			// This does the same as above, only checking if we are on the last page, and then generating the Next and Last links.
			if ($this->page_current == $this->page_last) 
			{
			?>
				<div class="btn-group">
					<button 
						type		="button" 
						name		="paging_next"
						id			="paging_next"
						class		="btn btn-primary btn-sm disabled" 
						title		="Go to the next page."
						><span class="glyphicon glyphicon-forward"></span></button>
				</div>
                
                <div class="btn-group">
					<button 
						type		="button" 
						name		="paging_prev"
						id			="paging_prev"
						class		="btn btn-primary btn-sm disabled" 
						title		="Go to the last page."
						><span class="glyphicon glyphicon-fast-forward"></span></button>
				</div>
           
            <?php
			} 
			else 
			{		
				// Build URL query for "next" page.		
				$url_query->set_data(self::REQUEST_KEY_PAGE_NUMBER, $this->page_current+1);							
			?>
            	<a                    
                    class		="btn btn-primary btn-sm" 
                    title		="Go to the next page."
                    href="<?php echo $url_query->return_url_encoded(); ?>"><span class="glyphicon glyphicon-forward"></span></a>
            
			<?php
				// Build URL query for "last" page.
				$url_query->set_data(self::REQUEST_KEY_PAGE_NUMBER, $this->page_last);				
			?>    
                <a                    
                    class		="btn btn-primary btn-sm" 
                    title		="Go to the last page."
                   	href="<?php echo $url_query->return_url_encoded(); ?>"><span class="glyphicon glyphicon-fast-forward"></span></a>
            
			<?php
            }			
			?>           
            </div>
            
            <!--Current page inidicator-->
            <br /><span class="text-muted">Page <?php echo $this->page_current; ?> of <?php echo $this->page_last; ?> (<?php echo $this->row_count_total; ?>
            <?php echo ($this->row_count_total == 1 ? 'record' : 'records'); ?>)</span>
			
			<?php
			
			// Collect contents from cache and then clean it.
			$this->markup = ob_get_contents();
			ob_end_clean();
			
			return $this->markup;
		}
	}

?>