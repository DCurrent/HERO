<?php 
	
	require_once(__DIR__.'/source/main.php');
	require_once(__DIR__.'/source/common_functions/common_list.php');
	require_once(__DIR__.'/source/common_functions/common_check_action.php');
	require_once(__DIR__.'/source/common_functions/common_security.php');
	
	const LOCAL_STORED_PROC_NAME 	= 'config_form'; 	// Used to call stored procedures for the main record set of this script.
	const LOCAL_BASE_TITLE 			= 'Common Forms';	// Title display, button labels, instruction inserts, etc.
	const PRIMARY_DATA_CLASS		= '\data\ConfigCommonEntry';
		
	// Save this record.
	function action_save()
	{		
		
	
		// Initialize database query object.
		$query 	= new \dc\yukon\Database();
		
		// Set up account info.
		$access_obj = new \dc\access\status();
				
		// Initialize main data class and populate it from
		// post variables.
		$primary_data_class = PRIMARY_DATA_CLASS;
		$_main_data = new $primary_data_class();						
		$_main_data->populate_from_request();
	
		// Call update stored procedure.
		$query->set_sql('CREATE TABLE [dbo].[@param_table_name = ?](
	[id_key] [int] NOT NULL,
	[label] [varchar](50) NULL,
	[details] [varchar](max) NULL,
 CONSTRAINT [PK_tbl_config_table] PRIMARY KEY CLUSTERED 
(
	[id_key] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


ALTER TABLE [dbo].[tbl_config_table]  WITH CHECK ADD  CONSTRAINT [FK_tbl_config_table_tbl_master] FOREIGN KEY([id_key])
REFERENCES [dbo].[tbl_master] ([id_key])
ON UPDATE CASCADE
ON DELETE CASCADE

ALTER TABLE [dbo].[tbl_config_table] CHECK CONSTRAINT [FK_tbl_config_table_tbl_master]');
												
		$params = array(array('tbl_config_table', 		SQLSRV_PARAM_IN));
		
		$query->set_params($params);			
		$query->query();
		
		// Repopulate main data object with results from merge query.
		// We can use common data here because all we need
		// is the ID for redirection.
		
		//$query->get_line_params()->set_class_name('\data\Common');
		//$_main_data = $query->get_line_object();
		
		// Now that save operation has completed, reload page using ID from
		// database. This ensures the ID is always up to date, even with a new
		// or copied record.
		
		//header('Location: '.$_SERVER['PHP_SELF'].'?id='.$_main_data->get_id());  
	}
	
	// Verify user access.
	common_security();
		
	// Start page cache.
	$page_obj = new \dc\cache\PageCache();
	
	// Main navigaiton.
	$obj_navigation_main = new class_navigation();	
	
	// Record navigation - also gets user record action requests.
	$obj_navigation_rec = new \dc\recordnav\RecordNav();
	
	// Apply user action request (if any). Depending on the
	// action, the page may be reloaded with the same or
	// another ID.
	common_check_action($obj_navigation_rec->get_action());
	
	// Initialize database query object.
	$query 	= new \dc\yukon\Database();
	
	// Initialize a blank main data object.
	$primary_data_class = PRIMARY_DATA_CLASS;
	$_main_data = new $primary_data_class();	
		
	// Populate from request so that we have an 
	// ID and KEY ID (if nessesary) to work with.
	$_main_data->populate_from_request();
	
	// Set up primary query with parameters and arguments.
	$query->set_sql('{call '.LOCAL_STORED_PROC_NAME.'(@param_filter_id = ?,
									@param_filter_id_key = ?)}');
	$params = array(array($_main_data->get_id(), 		SQLSRV_PARAM_IN),
					array($_main_data->get_id_key(), 	SQLSRV_PARAM_IN));

	// Apply arguments and execute query.
	$query->set_params($params);
	$query->query();
	
	// Get navigation record set and populate navigation object.		
	$query->get_line_params()->set_class_name('\dc\recordnav\RecordNav');	
	if($query->get_row_exists() === TRUE) $obj_navigation_rec = $query->get_line_object();	
	
	// Get primary data record set.	
	$query->get_next_result();
	
	$query->get_line_params()->set_class_name(PRIMARY_DATA_CLASS);	
	if($query->get_row_exists() === TRUE) $_main_data = $query->get_line_object();		
	
?>

<!DOCtype html>
<html lang="en">
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <title><?php echo APPLICATION_SETTINGS::NAME. ', '.LOCAL_BASE_TITLE; ?></title>        
        
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="source/css/style.css" />
        <link rel="stylesheet" href="source/css/print.css" media="print" />
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>     
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>        
        
        <!-- WYSIWYG Text boxes -->
		<script type="text/javascript" src="source/javascript/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="source/javascript/tinymce/settings.js"></script>
    </head>
    
    <body>    
        <div id="container" class="container">            
            <?php echo $obj_navigation_main->generate_markup_nav(); ?>                                                                                
            <div class="page-header">           
                <h1><?php echo LOCAL_BASE_TITLE; ?></h1>
                <p class="lead">This screen allows for adding and editing <?php echo LOCAL_BASE_TITLE; ?> records.</p>
                <?php require(__DIR__.'/source/common_includes/revision_alert.php'); ?>
            </div>
            
            <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">           
           		<?php echo $obj_navigation_rec->generate_button_list(); ?>  
                <hr />
                
                <?php require(__DIR__.'/source/common_includes/details_field.php'); ?>
                
             	<div class="form-group">       
                    <label class="control-label col-sm-2" for="revision">Revision</label>
                    <div class="col-sm-10">
                        <p class="form-control-static"> 
                        <?php if(is_object($_main_data->get_create_time()))
								{
								?>
                                <a id="revision" href = "common_version_list.php?id=<?php echo $_main_data->get_id();  ?>"
                                                            data-toggle	= ""
                                                            title		= "View log for this record."
                                                             target		= "_new" 
                            	><?php  echo date(APPLICATION_SETTINGS::TIME_FORMAT, $_main_data->get_create_time()->getTimestamp()); ?></a>
                        		<?php
								}
								else
								{
								?>
                                	<span class="alert-success">New Record. Fill out form and save to create first revision.</span>
                                <?php
								}
								?>
                                
                    	</p>
                    </div>
                </div>
                
                <div class="form-group">       
                    <label class="control-label col-sm-2">Test Links</label>
                    <div class="col-sm-10">
                        <p class="form-control-static"> 
                        <?php if($_main_data->get_id() && $_main_data->get_id() != -1)
								{
								?>
                                <a id="test_link_entry" href = ".?id_form=<?php echo $_main_data->get_id();  ?>&amp;id=-1"
                                                            data-toggle	= ""
                                                            title		= "Test entry form."
                                                             target		= "_new" 
                            	>Entry</a>
                                &nbsp; &middot; &nbsp;
                                <a id="test_link_list" href = ".?id_form=<?php echo $_main_data->get_id();  ?>&amp;list=1"
                                                            data-toggle	= ""
                                                            title		= "Test list form."
                                                             target		= "_new" 
                            	>List</a>
                        		<?php
								}
								else
								{
								?>
                                	<span class="alert-success">New Record. Fill out form and save to create test links.</span>
                                <?php
								}
								?>
                                
                    	</p>
                    </div>
                </div>
                
                <div class="form-group">       
                    <label class="control-label col-sm-2" for="label">Label</label>
                    <div class="col-sm-10">
                        <input 
                            type	="text" 
                            class	="form-control"  
                            name	="label" 
                            id		="label" 
                            placeholder="Label" 
                            value="<?php echo trim($_main_data->get_label()); ?>">
                    </div>
                </div> 
                
                <div class="form-group">       
                    <label class="control-label col-sm-2" for="slug">Slug</label>
                    <div class="col-sm-10">
                        <p class="small">This is a human readable search that is used to access the form with desired data via URL. Make sure to only use URL compatible text.</p>
                        <input 
                            type	="text" 
                            class	="form-control"  
                            name	="slug" 
                            id		="slug" 
                            placeholder="Search slug." 
                            value="<?php echo trim($_main_data->get_slug()); ?>">
                    </div>
                </div>   
                
                <div class="form-group">       
                    <label class="control-label col-sm-2" for="title">Title</label>
                    <div class="col-sm-10">
                        <p class="small">This is the marque title that is displayed in bold at top of page and in the task bar.</p>
                        <input 
                            type	="text" 
                            class	="form-control"  
                            name	="title" 
                            id		="title" 
                            placeholder="Display title." 
                            value="<?php echo trim($_main_data->get_title()); ?>">
                    </div>
                </div>
                
                <div class="form-group">       
                    <label class="control-label col-sm-2" for="description">Description</label>
                    <div class="col-sm-10">
                    	<p class="small">Description will appear directly below the title and tells the end user what the form is for.</p>
                    	<textarea class="form-control wysiwyg" rows="2" name="description" id="description"><?php echo $_main_data->get_description(); ?></textarea>                          
                    </div>
                </div>
                
                <div class="form-group">       
                    <label class="control-label col-sm-2" for="main_sql_name">Main SQL Name</label>
                    <div class="col-sm-10">
                        <p class="small">Stored procedure name that form will call for its primary record sets.</p>
                        <input 
                            type	="text" 
                            class	="form-control"  
                            name	="main_sql_name" 
                            id		="main_sql_name" 
                            placeholder="SQL Stored Procedure Name." 
                            value="<?php echo trim($_main_data->get_main_sql_name()); ?>">
                    </div>
                </div> 
                
                <div class="form-group">       
                    <label class="control-label col-sm-2" for="main_object_name">Main Object Name</label>
                    <div class="col-sm-10">
                        <p class="small">Name of class that will be called and used to create object for form's main data.</p>
                        <input 
                            type	="text" 
                            class	="form-control"  
                            name	="main_object_name" 
                            id		="main_object_name" 
                            placeholder="Object class name." 
                            value="<?php echo trim($_main_data->get_main_object_name()); ?>">
                    </div>
                </div>
                
                <hr />
                <div class="form-group">
                	<div class="col-sm-12">
                		<?php echo $obj_navigation_rec->get_markup_cmd_save_block(); ?>
                	</div>
                </div> 
                
                             
            </form>
            
            <?php echo $obj_navigation_main->generate_markup_footer(); ?>
        </div><!--container-->        
		<script src="source/javascript/verify_save.js"></script>
		<script>
            //Google Analytics Here// 
        
            $(document).ready(function(){
            });
        </script>
	</body>
</html>

<?php
	// Collect and output page markup.
	echo $page_obj->markup_and_flush();
?>