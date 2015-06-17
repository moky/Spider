<?php
	
	
	class Log {
		
		function info($msg) {
			echo "$msg\n";
		}
		
		function warning($msg) {
			echo "WARNING >> $msg\n";
		}
		
		function error($msg) {
			echo "*ERROR* >> $msg\n";
		}
		
	}
	
