<?php
	
	
	class Log {
		
		function info($msg) {
			echo self::__DEBUG__('I') . "$msg\n";
		}
		
		function warning($msg) {
			echo self::__DEBUG__('W') . "$msg\n";
		}
		
		function error($msg) {
			echo self::__DEBUG__('E') . "$msg\n";
		}
		
		//
		// protected:
		//
		
		function __DEBUG__($type) {
			//
			// info
			//
			if ($type == 'I') {
				return '>> ';
			}
			
			// get debug info
			$trace = debug_backtrace();
			$count = count($trace);
			if ($count < 2) {
				// error
				return '';
			}
			array_shift($trace); // remove the last calling
			$count--;
			
			// get 1st level info
			$line = $trace[0]['line'];
			$file = $trace[0]['file'];
			$pos = strrpos($file, '/');
			if ($pos !== false) {
				$file = substr($file, $pos + 1);
			}
			
			//
			// error
			//
			if ($type == 'E') {
				return "[$file:$line] *ERROR* >> ";
			}
			
			// get 2nd level info
			$clazz = null;
			$func = null;
			if ($count > 1) {
				if (array_key_exists('class', $trace[1])) {
					$clazz = $trace[1]['class'];
				}
				if (array_key_exists('function', $trace[1])) {
					$func = $trace[1]['function'];
				}
			}
			
			//
			// warning
			//
			if ($type == 'W') {
				if ($clazz && $func) {
					return "$clazz::$func() WARNING >> ";
				} else if ($func) {
					return "[$file:$line] $func() WARNING >> ";
				} else {
					return "[$file:$line] WARNING >> ";
				}
			}
			
			return "[$file:$line] $clazz::$func() >> ";
		}
		
	}
	
