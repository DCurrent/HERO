
// dc_guid
// Caskey, Damon V.
// 2016-06-19
//
// License: www.caskeys.com/dc/wp-admin/post.php?post=5067

// Output a simulated guid. Note these should be used only
// for unique HTML IDs and other items where a unique ID
// is needed but data integrity is not paramount.
function dc_guid() 
{
	var $result = null;
	
	$result = sector();
	$result += sector(); 
	$result += '-'; 
	$result += sector();
	$result += '-'; 
	$result += sector();
	$result += '-'; 
	$result += sector(); 
	$result += '-';
	$result += sector();
	$result += sector();
	$result += sector();
	
	return $result;
}

function sector() 
{
	var $result = null;
	
	$result = Math.floor((1 + Math.random()) * 0x10000);
	$result = $result.toString(16);
	$result = $result.substring(1);
	
	return $result;
}