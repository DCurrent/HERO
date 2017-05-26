<?php 
	
	require(__DIR__.'/source/main.php');
	require(__DIR__.'/source/common_functions/common_security.php');
	require('../../libraries/vendor/mpdf/mpdf.php');	// pdf maker.
	
	const LOCAL_STORED_PROC_NAME 	= 'stf_observation_target_read'; 	// Used to call stored procedures for the main record set of this script.
	const LOCAL_BASE_TITLE 			= 'Observation';					// Title display, button labels, instruction inserts, etc.
	$primary_data_class				= '\data\Area';

		
	
	// Initialize pdf maker class.
	$pdf_gen = new mPDF();
	
	$pdf_gen->SetTitle('EHS Class Certificate');	
	$pdf_gen->SetCreator('Caskey, Damon V.');
	$pdf_gen->AddPage('L'); // Adds a new page in Landscape orientation	
	
	// Verify user access.
	common_security();
		
	// Start page cache.
	$page_obj = new \dc\cache\PageCache();
		
?>
<html lang="en">
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <title><?php echo APPLICATION_SETTINGS::NAME. ', '.LOCAL_BASE_TITLE; ?></title>        
        
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        
        
        <style>
						
			.incident {
				font-size:larger;			
			}
			
			ul.checkbox  { 
				
			 	-webkit-column-count: 3;  				
				-moz-column-count: auto;				
			  column-count: 3;			 
			  margin: 10; 
			  padding: 10; 
			  margin-left: 20px; 
			  list-style: none;			  
			} 
			
			ul.checkbox li input { 
			  margin-right: 30px; 
			  cursor:pointer;
			  padding: 10;
			} 
			
			ul.checkbox li { 
			  border: 1px transparent solid; 
			  display:inline-block;
			  width:12em;			  
			} 
			
			ul.checkbox li label { 
			  margin-right: 10px;
			  cursor:pointer;			  
			} 
			
		</style>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>     
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>        
        
        <!-- WYSIWYG Text boxes -->
		<script type="text/javascript" src="source/javascript/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="source/javascript/tinymce/settings.js"></script>
    </head>
    
    <body>    
       	<h1>Observation List - Under Construction</h1>
	</body>
</html>

<?php	
	// Collect contents from cache and then clean it.
	$content = $page_obj->markup_from_cache();
	$page_obj->clean_cache();
	
	$pdf_gen->SetFooter($footer);
	
	// Send contents to pdf gen.
	$pdf_gen->WriteHTML($content);

	// Send pdf and exit script.
	$pdf_gen->Output('observation_list', 'I');
	exit;
?>