<!--Details-->
<?php 
	$_common_details_class_add = NULL; 
	
	if($_main_data->get_details())
	{
		$_common_details_class_add = 'style="background-color:#dff0d8"';
	}
?>        

<div class="form-group" id="form-group-details">
  <div class="col-sm-12" id="details_container">
		<div class="panel panel-default">
			<div class="panel-heading" <?php echo $_common_details_class_add; ?>>
				<h4 id="h41" class="panel-title">
				<a class="accordion-toggle" data-toggle="collapse" href="#collapse_module_1"><span class="glyphicon glyphicon-list-alt"></span> Details &amp; Notes<span class="glyphicon glyphicon-menu-down pull-right"></span></a>
				</h4>
			</div>
		
		<div style="" id="collapse_module_1" class="panel-collapse collapse">
				<div class="panel-body"> 
					<textarea class="form-control wysiwyg" rows="5" name="details" id="details"><?php echo $_main_data->get_details(); ?></textarea>                        
				</div>
			</div>
		</div>
	</div><!-- #details_container -->
</div><!-- #form-group-details -->
