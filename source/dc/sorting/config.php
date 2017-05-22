<?php

	namespace dc\sorting;

	abstract class URL_KEY
	{
		// If these constant values are changed, make sure to update
		// the mutator methods as well.
		const
			ORDER	= 'order',
			FILTER	= 'field';
	}
	
	abstract class ORDER_MARKUP
	{
		const 		
			ASCENDING	= '<span class="glyphicon glyphicon glyphicon-sort-by-alphabet"></span>',
			DECENDING	= '<span class="glyphicon glyphicon glyphicon-sort-by-alphabet-alt"></span>',
			NONE 		= '<span class="glyphicon glyphicon glyphicon-sort"></span>';
	}
	
	abstract class ORDER_TYPE
	{
		const 
			ASCENDING 	= 0,
			DECENDING	= 1;			
	}

?>