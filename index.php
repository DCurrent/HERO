<?php 

	require(__DIR__.'/source/main.php');
	
	function common_form_redirect()
	{
		$result = FALSE;
		$request_form = NULL;
		$request_list = NULL;
		
		if(isset($_REQUEST['id_form']))
		{
			$request_form = $_REQUEST['id_form'];
		}
		else
		{
			return $result;
		}
		
		if(isset($_REQUEST['list']))
		{
			$request_list = $_REQUEST['list'];
		}
		
		$database = new \dc\yukon\Database();
		
		$_main_data = new \dc\application\CommonEntry();	
		
		// Populate from request so that we have an 
		// ID and KEY ID (if nessesary) to work with.
		$_main_data->populate_from_request();
		
		// Set up primary query with parameters and arguments.
		$database->set_sql('{call config_form(@param_filter_id = ?)}');
		$params = array(array($request_form, 		SQLSRV_PARAM_IN));
	
		// Apply arguments and execute query.
		$database->set_params($params);
		$database->query();
		
		// Skip navigation data and get primary data record set.	
		$database->get_next_result();
		
		$database->get_line_params()->set_class_name('\dc\application\CommonEntry');	
		if($database->get_row_exists() === TRUE) 
		{
			$_main_data = $database->get_line_object();
			
			if($_main_data->get_file_name())
			{
				$base_target = $_main_data->get_file_name();
			}
			else
			{
				$base_target = 'common_entry.php';
			}
			
			// Open record navigation object so we can
			// get variables for redirect URL.
			$obj_navigation_rec = new \dc\recordnav\RecordNav();	
			
			// Initialize redirect url object and 
			// populate variables.
			$url_query	= new \dc\url\URLFix;
			$url_query->set_data('action', $obj_navigation_rec->get_action());
			$url_query->set_data('id', $obj_navigation_rec->get_id());
			$url_query->set_data('id_key', $obj_navigation_rec->get_id_key());
			$url_query->set_data('id_form', $request_form);
			
			if($request_list)
			{
				// Final result, and the target forwarding destination.
				$result 	= '#';
			
				// First thing we need is the self path.				
				$file = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);
				
				// List giles are the name of a single record file
				// with _list added on, so all we need to do is
				// remove the file suffix, and add '_list.php' to
				// get the list file's name. This is also all we
				// need for forwarding purposes.	
				$target_name	= basename($base_target, '.php').'_list.php';		
				
				// To verify the list file exists, we have to target the
				// file system path. We can combine the document root
				// and self's directory to get it.
				$root			= filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_URL);
				$directory 		= dirname($file);
				$target_file	= $root.$directory.'/'.$target_name;
				
				// Does the list file exisit? If so we can
				// redirect to it. Otherwise, do nothing.
				if(file_exists($target_file))
				{	
					// Set target url.					
					$result = $target_name;
				}
				else
				{
					$result = $base_target;
				}								
			}
			else
			{
				$result = $base_target;
			}
			
			$url_query->set_url_base($result);
			
			header('Location: '.$url_query->return_url());			
			exit;
		}
				
	}	
	
	common_form_redirect();
	
	$page_obj = new \dc\cache\PageCache();
	
	$access_obj_process = new \dc\access\process();
	$access_obj_process->get_config()->set_authenticate_url(APPLICATION_SETTINGS::DIRECTORY_PRIME);	
	$access_obj_process->process_control();
	
	//var_dump($_POST);
	//echo '<br />';
	//var_dump($_SESSION);
	
	//Get and verify log in status.
	$access_obj = new \dc\access\status();
	$access_obj->get_config()->set_authenticate_url(APPLICATION_SETTINGS::DIRECTORY_PRIME);	
	$access_obj->verify();
	
	// Set up navigaiton.
	$navigation_obj = new class_navigation();
	$navigation_obj->generate_markup_nav();
	$navigation_obj->generate_markup_footer();
?>

<!DOCtype html>
<html lang="en">
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <title><?php echo APPLICATION_SETTINGS::NAME; ?></title>        
        
         <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="source/css/style.css" />
        <link rel="stylesheet" href="source/css/print.css" media="print" />
        
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        
        <!-- Latest compiled JavaScript -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    </head>
    
    <body>          
        <!-- Modal -->
        <div id="help_link_blue" class="modal fade" role="dialog">
          <div class="modal-dialog">
        
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Link Blue</h4>
              </div>
              <div class="modal-body">
                <p>Link Blue is the University of Kentucky's campus wide Active Directory login. It is the same account name and password you use to log into a workstation. <a href="//www.uky.edu/UKHome/subpages/linkblue.html" target="_blank">Click here</a> for more information.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>        
          </div>
        </div>
    
        <div id="container" class="container">            
            <?php echo $navigation_obj->get_markup_nav(); ?>                                                                                
            <div class="page-header">
                <h1><?php echo APPLICATION_SETTINGS::NAME; ?></h1>
                <p class="lead">Welcome to <?php echo APPLICATION_SETTINGS::NAME; ?>, our observation system for tracking potential hazards like slip, trip, and fall conditions. For more information about slips and falls, please see the following video.</p>
				<?php
				
					echo '<!--account:'.$access_obj->get_account().'-->';
					echo '<!--id:'.$access_obj->get_id().'-->';
					// Logged in?
					if($access_obj->get_account())
					{
						// Get current hour in the 24 hour clock format.
						$time = date('H');
						
						// Give user a greeting based on hour of the day.
						if ($time < '12') 
						{
							echo 'Good morning ';
						} 
						else if ($time >= '12' && $time < '17') 
						{
							echo 'Good afternoon ';
						} 
						else if ($time >= "17") 
						{
							echo 'Good evening ';
						}
						echo $access_obj->get_name_f();
				?>, thanks for participating! You can use the navigation menu above to start up a new observation or view any previous observations you have made.</p>
                <?php
					}
					else
					{
				?>
                		<p class="lead">In order to use <?php echo APPLICATION_SETTINGS::NAME; ?>, please log in using your <a href="#" data-toggle="modal" data-target="#help_link_blue">Link Blue</a> account and password.</p>
            		
                    	<p><?php echo $access_obj->dialog(); ?></p>
                    	
                        <!--Note: PHP self is nessesary to override any link vars.-->
                        <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input type="text" class="form-control" name="account" id="account" placeholder="Link Blue Account" required>
                            </div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input type="password" class="form-control" name="credential" id="credential" placeholder="Password" required>
                            </div>
                            
                            <br>
                            
                            <button type="submit" name="access_action" value="<?php echo \dc\access\ACTION::LOGIN; ?>" class="btn btn-default"><span class="glyphicon glyphicon-log-in"></span> Login</button>
                        </form>
            
                <?php
					}					
				?>
           	
           		<br />
           		&nbsp;
           		<p class="center-block">
           		<iframe width="560" height="315" src="https://www.youtube.com/embed/Bckj4zErK-Q" frameborder="0" allowfullscreen></iframe>
            	</p>
            </div> 
                    
            <?php echo $navigation_obj->get_markup_footer(); ?>
        </div><!--container-->        
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40196994-1', 'uky.edu');
  ga('send', 'pageview');
</script>
</body>
</html>

<?php
	// Collect and output page markup.
	echo $page_obj->markup_and_flush();
?>