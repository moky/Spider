<?php
	
	require_once('XMLElement.class.php');
	
	
	class XML {
		
		protected $data = null;
		
		public function __construct($string) {
			$this->data = $string;
		}
		
		//
		// element
		//
		public function fetch_element($name, &$seek = 0) {
			if ($this->data == null) {
				return null;
			}
			
			// start
			$start = stripos($this->data, '<'.$name.' ', $seek);
			if ($start === false) {
				// not found
				$seek = strlen($this->data);
				return null;
			}
			
			// end
			$end1 = stripos($this->data, '</'.$name.'>', $start);
			$end2 = strpos($this->data, '/>', $start);
			$end3 = strpos($this->data, '>', $start);
			if ($end1 === false && $end2 === false && $end3 === false) {
				// error
				$seek = $start + strlen($name) + 2;
				return null;
			}
			
			while ($end1 !== false) {
				if ($end2 !== false && $end2 < $end1) break;
				//if ($end3 !== false && $end3 < $end1) break;
				$seek = $end1 + strlen($name) + 3;
				break;
			}
			while ($end2 !== false) {
				if ($end1 !== false && $end1 < $end2) break;
				//if ($end3 !== false && $end3 < $end2) break;
				$seek = $end2 + 2;
				break;
			}
			while ($end3 !== false) {
				if ($end1 !== false/* && $end1 < $end3*/) break;
				if ($end2 !== false/* && $end2 < $end3*/) break;
				$seek = $end3 + 1;
				break;
			}
			$element = substr($this->data, $start, $seek - $start);
			
			// cut at new line
			$end = strpos($element, "\n");
			if ($end !== false) {
				$element = substr($element, 0, $end);
				$element = trim($element);
			}
			
			return new XMLElement($element);
		}
		
		/**
		 *  Description:
		 *      search element by $name from $seek, ignore those $key-$value not match.
		 */
		public function fetch_element_with_attribute($name, $key, $value, $seek = 0) {
			if ($this->data == null) {
				return null;
			}
			
			$value = strtolower($value);
			$len = strlen($this->data);
			for (; $seek < $len;) {
				$element = $this->fetch_element($name, $seek);
				if ($element) {
					$attr = $element->fetch_attribute($key);
					if ($attr && strtolower($attr) == $value) {
						return $element;
					}
				}
			}
			// not found
			return null;
		}
		
	}
	
