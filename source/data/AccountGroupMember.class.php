<?php
	
	namespace data;

	interface iAccountGroupMember
	{
		//  Accessors.
		function get_member();
		
		//  Mutators
		function set_member($value);
	}
	
	class AccountGroupMember extends Common implements iAccountGroupMember
	{
		private
			$member	= NULL;
		
		// Accessors.	
		public function get_member()
		{
			return $this->member;
		}
		
		// Mutators.
		public function set_member($value)
		{
			$this->member = $value;
		}
	}
	
?>