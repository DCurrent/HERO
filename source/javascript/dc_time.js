// Caskey, Damon V.
// 2017-02-13
//
// Return date in one of two formmats 
// depending on HTML5 input type support.
// Intended for datetime-local.
//
// Requirements:
//
// Modernizr 
// Moment
function iChronofix_format($date, $format_supported, $format_not_supported)
{
	var $result		= null;		// Final result.
	var $format 	= null; 	// Format to apply.
	var $supported	= false;	// Type supported?

	// Default arguments.
	if (typeof($format_supported)==='undefined') $format_supported = 'YYYY-MM-DDTHH:mm';
	if (typeof($format_not_supported)==='undefined') $format_not_supported = 'YYYY-MM-DD HH:mm';

	// Use modernizer library to check for
	// support of datetime-local.
	$supported = Modernizr.inputtypes['datetime-local'];
	
	// Which format to use?
	if($supported)
	{
		$format = $format_supported;
	}
	else
	{
		$format = $format_not_supported;
	}
	
	// Use moment library to output
	// date & time.  
	$result = moment($date).format($format);				

	// Return results.
	return $result;
}