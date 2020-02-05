<?php 
	
	require(__DIR__.'/source/main.php');
	require(__DIR__.'/source/common_functions/common_security.php');
	
	const LOCAL_STORED_PROC_NAME 	= 'stf_observation_target_read'; 	// Used to call stored procedures for the main record set of this script.
	const LOCAL_BASE_TITLE 			= 'Observation Confirmed';			// Title display, button labels, instruction inserts, etc.
	$primary_data_class				= '\data\Area';
	
	// Verify user access.
	common_security();
		
	// Start page cache.
	$page_obj = new \dc\cache\PageCache();
	
	// Main navigaiton.
	$obj_navigation_main = new class_navigation();	
	
	// Record navigation - also gets user record action requests.
	$obj_navigation_rec = new \dc\recordnav\RecordNav();
	
?>

<!DOCtype html>
<html lang="en">
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <title><?php echo APPLICATION_SETTINGS::NAME. ', '.LOCAL_BASE_TITLE; ?></title>        
        
        <link rel="stylesheet" href="source/bootstrap/style.css">
               
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>     
        <script src="source/bootstrap/script.js"></script>        
        
    </head>
    
    <body>    
        <div id="container" class="container">            
            <?php echo $obj_navigation_main->generate_markup_nav(); ?>                                                                                
            <div class="page-header">           
                <h1><?php echo LOCAL_BASE_TITLE; ?></h1>
                <p class="lead">We have received and recorded your observation. If you would like to make another observation, please <a href="observation_target_read.php?id=-1">click here</a>. Otherwise you may close this page. Thank you!</p>
            </div>				
            
            <?php echo $obj_navigation_main->generate_markup_footer(); ?>
        </div><!--container-->
	</body>
</html>

<?php
	// Collect and output page markup.
	echo $page_obj->markup_and_flush();
?>