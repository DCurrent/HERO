<?php
	// If this isn't the active version, better alert user.
	if(!$_main_data->get_active() && $_main_data->get_id_key())
	{
	?>
		<div class="alert alert-warning">
			<strong>Notice:</strong> You are currently viewing an inactive revision of this record. Saving will make this the active revision. To return to the currently active revision without saving, click <a href="<?php echo $_SERVER['PHP_SELF'].'?id='.$_main_data->get_id(); ?>">here</a>.
		</div>
	<?php
	}
?>