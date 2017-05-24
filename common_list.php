<?php 
		
	require(__DIR__.'/source/main.php');
	require(__DIR__.'/source/common_functions/common_security.php');
	
	const LOCAL_STORED_PROC_NAME 	= 'config_form'; 		// Used to call stored procedures for the main record set of this script.
	const LOCAL_BASE_TITLE 			= '';					// Title display, button labels, instruction inserts, etc.
	$primary_data_class				= '';
	
	function get_layout()
	{
		$result = NULL;
		$request_form = NULL;
		
		if(isset($_REQUEST['id_form']))
		{
			$request_form = $_REQUEST['id_form'];
		}
		else
		{
			return $result;
		}
		// Initialize database query object.
		$database 	= new \dc\yukon\Database();
		
		// Set up primary query with parameters and arguments.
		$database->set_sql('{call '.LOCAL_STORED_PROC_NAME.'(@param_filter_id = ?)}');
									
		$params = array(array($request_form, SQLSRV_PARAM_IN));	
					
		// Apply arguments and execute query.
		$database->set_params($params);
		$database->query();
		
		// Skip navigation data and get primary data record set.	
		$database->get_next_result();
		
		$database->get_line_params()->set_class_name('\dc\application\CommonEntry');	
		
		if($database->get_row_exists() === TRUE) 
		{
			$result = $database->get_line_object();
		}
		else
		{
			header('Location: '.__DIR__);
		}
		
		return $result;
	}
	
	$_layout = get_layout();
	
	class class_filter_control extends Common
	{
		const _DATE_FORMAT  = 'Y-m-d H:i:s'; 
		
		private			
			$building	= NULL,
			$floor		= NULL,
			$time_obj	= NULL,
			$time_end 	= NULL,
			$time_start	= NULL;
		
		public function __construct(iChronofix $iChronofix)
		{
			$this->time_obj = $iChronofix;	
		}
		
		// Accessors
		public function get_time_end()
		{
			return $this->time_end;
		}
		
		public function get_time_start()
		{
			return $this->time_start;
		}
		
		// Mutators
		public function set_time_end($value)
		{
			// Run time through sanitizer to 
			// get a valid and uniform date/time.
			$this->time_obj->set_time($value);
			$this->time_obj->sanitize();
			
			// Set member.			
			$this->time_end = $this->time_obj->get_time();
		}
		
		public function set_time_start($value)
		{	
			// Run time through sanitizer to 
			// get a valid and uniform date/time.
			$this->time_obj->set_time($value);
			$this->time_obj->sanitize();
			
			// Set member.			
			$this->time_start = $this->time_obj->get_time();	
		}
	} 
	
	// Verify user access.
	common_security();
	
	// Start page cache.
	$page_obj = new \dc\cache\PageCache();
	
	// Main navigaiton.
	$obj_navigation_main = new class_navigation();
	
	// Initialize time library with settings.
	$iChronofix 		= new \dc\chronofix\Chronofix();
	
	$filter_control = new class_filter_control($iChronofix);
	$filter_control->populate_from_request();
	
	// Establish sorting object, set defaults, and then get settings
	// from user (if any).
	$sorting = new \dc\sorting\SortControl;
	$sorting->set_sort_field(1);
	$sorting->set_sort_order(\dc\sorting\ORDER_TYPE::ASCENDING);
	$sorting->populate_from_request();
	
	// Set up navigaiton.
	$navigation_obj = new class_navigation();
	
	// Set up database.
	$query = new \dc\yukon\Database();
		
	$paging = new \dc\recordnav\Paging();
	$paging->set_row_max(APPLICATION_SETTINGS::PAGE_ROW_MAX);
	
	// Record navigation.
	$obj_navigation_rec = new \dc\recordnav\RecordNav();
	
	$query->set_sql('{call '.$_layout->get_main_sql_name().'_list(@param_page_current 		= ?,														 
										@param_page_rows 	= ?,
										@param_sort_field	= ?,
										@param_sort_order	= ?,
										@param_date_start	= ?,
										@param_date_end		= ?)}');	
	
	$params = array(array($paging->get_page_current(), 			SQLSRV_PARAM_IN), 
					array($paging->get_row_max(), 				SQLSRV_PARAM_IN),
					array($sorting->get_sort_field(),			SQLSRV_PARAM_IN),
					array($sorting->get_sort_order(),			SQLSRV_PARAM_IN),
					array($filter_control->get_time_start(),	SQLSRV_PARAM_IN),
					array($filter_control->get_time_end(),		SQLSRV_PARAM_IN));

	// Debugging tools
	//var_dump($params);
	//exit;

	$query->set_params($params);
	$query->query();
	
	$query->get_line_params()->set_class_name($primary_data_class);
	$_obj_data_main_list = $query->get_line_object_list();

	// --Paging
	$query->get_next_result();
	
	$query->get_line_params()->set_class_name('\dc\recordnav\Paging');
	
	//$_obj_data_paging = new \dc\recordnav\Paging();
	if($query->get_row_exists()) $paging = $query->get_line_object();
?>

<!DOCtype html>
<html lang="en">
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <title><?php echo APPLICATION_SETTINGS::NAME; 
		
			// Add page title, if any.
			if($_layout->get_title())
			{
				echo ', '.$_layout->get_title();
            }?> List</title>        
        
         <!-- CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <!--link rel="stylesheet" href="source/css/jquery.datetimepicker.min.css" /-->
        <link rel="stylesheet" href="source/css/style.css" />
        <link rel="stylesheet" href="source/css/print.css" media="print" />
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script src="source/javascript/modernizr.js"></script>
        <script src="source/javascript/iChronofix.js"></script>       
    </head>
    
    <body>    
        <div id="container" class="container">            
            <?php echo $navigation_obj->generate_markup_nav(); ?>                                                                                
            <div class="page-header">
                <h1><?php
					// Add page title, if any.
					if($_layout->get_title())
					{
						echo $_layout->get_title();
					}?></h1>
                <?php
					// Add page description, if any.
					if($_layout->get_description())
					{
						//echo $_layout->get_description();
					}?>
                <p class="lead">This form allows you to view the complete <?php echo strtolower(LOCAL_BASE_TITLE);?> list. Click any row to view details.</p>
            </div>
            
            	<div class="panel panel-default" id="filter_container">
                    <div class="panel-heading" id="filter_header">
                        <h4 id="h41" class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" href="#collapse_module_1"><span class="glyphicon glyphicon-filter"></span><span class="glyphicon glyphicon-menu-down pull-right"></span> Filters</a>
                        </h4>
                    </div><!--#filter_header-->
                
                	<div style="" id="collapse_module_1" class="panel-collapse collapse" id="filter_collapse">
                        <div class="panel-body" id="filter_body">                         
                                                       
                            <form class="form-horizontal" role="form" id="form_filter" method="get">
            	                
                                <input type="hidden" name="id" value="<?php echo $filter_control->get_id(); ?>" />
                                <input type="hidden" name="field" value="<?php echo $sorting->get_sort_field(); ?>" />
                                <input type="hidden" name="order" value="<?php echo $sorting->get_sort_order(); ?>" />
                                                         
                                <div class="form-group" id="group_filter_time">
                                    <label class="control-label col-sm-2" for="filter_time_start">Time (from):</label>
                                    <div class="col-sm-4">
                                        <input 
                                            type	="datetime-local" 
                                            class	="form-control time_start_filter"  
                                            name	="time_start" 
                                            id		="time_start" 
                                            step	="1"                                            
                                            placeholder="yyyy-mm-dd hh:mm:ss"
                                            value="<?php echo $filter_control->get_time_start(); ?>">
                                    </div>
                                
                                    <label class="control-label col-sm-2" for="filter_time_end">Time (to):</label>
                                    <div class="col-sm-4">
                                        <input  
                                            name	="time_end" 
                                            type	="datetime-local" 
                                            class	="form-control time_end_filter" 
                                            id		="time_end" 
                                            step	="1"
                                            placeholder="yyyy-mm-dd hh:mm:ss" autocomplete="on"
                                            min="01"
                                            value="<?php echo $filter_control->get_time_end(); ?>">
                                    </div>
                                </div><!--#group_filter_time-->
                                
                                <button 
                                    type	="submit"
                                    class 	="btn btn-primary btn-block" 
                                    name	="set_filter" 
                                    id		="set_filter"
                                    title	="Apply selected filters to list."
                                    ><span class="glyphicon glyphicon-filter"></span>Apply Filters</button>       
                                    
                            </form><!--#form_filter-->                                       
                        </div><!--#filter_body-->
                    </div><!--#filter_collapse-->
                </div><!--#filter_container-->
            
            <?php
				// Clickable rows. Clicking on table rows
				// should take user to a detail page for the
				// record in that row. To do this we first get
				// the base name of this file, and remove "list".
				// 
				// The detail file will always have same name 
				// without "list". Example: area.php, area_list.php
				//
				// Once we have the base name, we can use script to
				// make table rows clickable by class selector
				// and passing a completed URL (see the <tr> in
				// data table we are making clickable).
				//
				// Just to ease in development, we verify the detail
				// file exists before we actually include the script
				// and build a complete URL string. That way if the
				// detail file is not yet built, clicking on a table
				// row does nothing at all instead of giving the end
				// user an ugly 404 error.
				//
				// Lastly, if the base name exists we also build a 
				// "new item" button that takes user directly
				// to detail page with a blank record.	
			 
				$target_url 	= '#';
				$target_name	= basename(__FILE__, '_list.php').'.php';
				$target_file	= __DIR__.'/'; //.$target_name;				
				
				// Does the file exisit? If so we can
				// use the URL, script, and new 
				// item button.
				if(file_exists($target_file))
				{
					// Prepare redirect url with variables.
					$url_query	= new \dc\url\URLFix;
					$url_query->set_url_base($target_name);
					$url_query->set_data('id_form', $_layout->get_id());
				?>
                	<script>
						// Clickable table row.
						jQuery(document).ready(function($) {
							$(".clickable-row").click(function() {
								window.document.location = '<?php echo $url_query->return_url(); ?>?id=' + $(this).data("href");
							});
						});
					</script>
                    
                    <?php
                    	$url_query->set_data('nav_command', \dc\recordnav\COMMANDS::NEW_BLANK);
						$url_query->set_data('id=', \dc\yukon\DEFAULTS::NEW_ID);
                    ?>
                    
                    <a href="<?php echo $url_query->return_url(); ?>" class="btn btn-success btn-block" title="Click here to start entering a new item."><span class="glyphicon glyphicon-plus"></span> <?php echo LOCAL_BASE_TITLE; ?></a>
                <?php
				}
				
				
			?>
            <!-- Top record paging controls -->
            <br />
          	<?php echo $paging->generate_paging_markup(); ?>
            
            <!--div class="table-responsive"-->
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th><a href="<?php echo $sorting->sort_url(2); ?>">Revision <?php echo $sorting->sorting_markup(2); ?></a></th>
                            <th><a href="<?php echo $sorting->sort_url(1); ?>">Label <?php echo $sorting->sorting_markup(1); ?></a></th>             
                        </tr>
                    </thead>
                    <tfoot>
                    	<tr>
                            <th><a href="<?php echo $sorting->sort_url(2); ?>">Revision <?php echo $sorting->sorting_markup(2); ?></a></th>
                            <th><a href="<?php echo $sorting->sort_url(1); ?>">Label <?php echo $sorting->sorting_markup(1); ?></a></th          
                        ></tr>
                    </tfoot>
                    <tbody>                        
                        <?php
                            if(is_object($_obj_data_main_list) === TRUE)
							{
								for($_obj_data_main_list->rewind(); $_obj_data_main_list->valid(); $_obj_data_main_list->next())
								{						
									$_obj_data_main = $_obj_data_main_list->current();
                            ?>
                                        <tr class="clickable-row" role="button" data-href="<?php echo $_obj_data_main->get_id(); ?>">
                                            <td><?php if(is_object($_obj_data_main->get_create_time()) === TRUE) echo date(APPLICATION_SETTINGS::TIME_FORMAT, $_obj_data_main->get_create_time()->getTimestamp()); ?></td>
                                            <td><?php echo $_obj_data_main->get_label(); ?></td>
                                        </tr>                                    
                            <?php								
                            	}
							}
                        ?>
                    </tbody>                        
                </table>  
            <?php 
				echo $paging->get_markup();
				echo $navigation_obj->generate_markup_footer(); 
			?>
        </div><!--container-->        
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		ga('create', 'UA-40196994-1', 'uky.edu');
		ga('send', 'pageview');

		// Reload window when gaining focus.
		var $blurred = false;
		window.onblur = function() { $blurred = true; };
		window.onfocus = function() { $blurred && (location.reload()); };
	
		// Let's handle date and time entry.
		// We want to use the jquery time picker,
		// but only if the browser doesn't
		// support a datetime-local type
		// field. We'll use modernizr library
		// to check for support and act accordingly.
		
		// Get support status of datetime-local field. 
		$supported = Modernizr.inputtypes['datetime-local'];
		
		var $format = 'Y-m-d h:m:s';
	
	
		// If the datetime-local type is not supported,
		// then we need to load the files needed for
		// timepicker, and execute it.
		if(!$supported){
			include('source/javascript/jquery.datetimepicker.full.min.js', function(){
				
				// Load css for the time picker.
				if (document.createStyleSheet){
					document.createStyleSheet('style.css');
				}
				else {
					$('head').append($('<link rel="stylesheet" href="source/css/jquery.datetimepicker.min.css" type="text/css" media="screen" />'));
				}
				
				// Apply time picker to fields.
				jQuery('.time_start_filter').datetimepicker({
					validateOnBlur:true,
					value:'<?php echo $filter_control->get_time_start(); ?>',
					format:$format
				});
				
				jQuery('.time_end_filter').datetimepicker({
					validateOnBlur:true,
					value:'<?php echo $filter_control->get_time_end(); ?>',
					format:$format
				});				
			});
		}
		else
		{
			include('source/javascript/moment.js', function(){	
				$format = 'YYYY-MM-DDTHH:mm:ss';
				
				// Datetime is supported, so just populate the fields.
				jQuery('.time_start_filter').val(moment('<?php echo $filter_control->get_time_start(); ?>').format($format));
				jQuery('.time_end_filter').val(moment('<?php echo $filter_control->get_time_end(); ?>').format($format));				
			});
		}
	
		// Conditonally load and fire script.
		function include(script,callback){
			$.getScript(script, function(){			
				if(typeof callback == 'function')
				callback.apply({},arguments);
			});
		}
</script>
</body>
</html>

<?php
	// Collect and output page markup.
	echo $page_obj->markup_and_flush();
?>