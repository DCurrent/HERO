// Include into page to alert users when exiting if they have unsaved changes.

// Example: $.getScript('source/javascript/verify_save.js', function() {});

// Global "saved" variable. 
var $unsaved = false;

// When user clicks one of the save keys, set unsaved to false.
$('.record_save').click(function(){
	$unsaved = false;
});


$(document).ready(function(){   	
	
	
	// Any time a field is modified, unsaved becomes true.
	$(':input').change(function(){
		$unsaved = true;
	});
	
	// When page unloads, fire this check.
	function unloadPage(){ 
		if($unsaved)
		{
			return 'You have unsaved changes. Discard changes and continue?';
		}
	}
	
	window.onbeforeunload = unloadPage; 
});

