<?php
	
	
	class Object {
		
		function __construct() {
			
		}
		
		function __destruct() {
			
		}
		
		function __toString() {
			return var_export($this, true);
		}
		
	}
	
