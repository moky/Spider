<?php
	
	
	class Path {
		
		// public:
		var $dirname   = null;
		var $basename  = null; // filename.ext
		
		var $filename  = null;
		var $extension = null;
		
		function __construct($string) {
			
			$pos = strpos($string, '?');
			if ($pos !== false) {
				$string = substr($string, 0, $pos);
			}
			$pos = strpos($string, '#');
			if ($pos !== false) {
				$string = substr($string, 0, $pos);
			}
			
			$dict = pathinfo($string);
			if ($dict) {
				$dict = new Dictionary($dict);
				$this->dirname   = $dict->dirname;
				$this->basename  = $dict->basename;
				$this->filename  = $dict->filename;
				$this->extension = $dict->extension;
			}
		}
		
		function __toString() {
			return $this->dirname . '/' . $this->filename . '.' . $this->extension;
		}
	}
	
