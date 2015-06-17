<?php
	
	require_once('Object.class.php');
	require_once('XMLElement.class.php');
	
	
	class XML extends Object {
		
		protected $data = null;
		
		public function __construct($string) {
			parent::__construct();
			
			$this->data = $string;
		}
		
		/**
		 *  Description:
		 *      search element by $name from $offset
		 */
		public function fetch_element($name, &$offset = 0) {
			if ($this->data == null) {
				return null;
			}
			
			// start
			$start = stripos($this->data, '<'.$name.' ', $offset);
			if ($start === false) {
				// not found
				$offset = strlen($this->data);
				return null;
			}
			
			// end
			$end1 = stripos($this->data, '</'.$name.'>', $start);
			$end2 = strpos($this->data, '/>', $start);
			$end3 = strpos($this->data, '>', $start);
			if ($end1 === false && $end2 === false && $end3 === false) {
				// error
				$offset = $start + strlen($name) + 2;
				return null;
			}
			
			while ($end1 !== false) {
				if ($end2 !== false && $end2 < $end1) break;
				//if ($end3 !== false && $end3 < $end1) break;
				$offset = $end1 + strlen($name) + 3;
				break;
			}
			while ($end2 !== false) {
				if ($end1 !== false && $end1 < $end2) break;
				//if ($end3 !== false && $end3 < $end2) break;
				$offset = $end2 + 2;
				break;
			}
			while ($end3 !== false) {
				if ($end1 !== false/* && $end1 < $end3*/) break;
				if ($end2 !== false/* && $end2 < $end3*/) break;
				$offset = $end3 + 1;
				break;
			}
			$element = substr($this->data, $start, $offset - $start);
			
			// cut at new line
			$end = strpos($element, "\n");
			if ($end !== false) {
				$element = substr($element, 0, $end);
			}
			
			return new XMLElement($element);
		}
		
		/**
		 *  Description:
		 *      search element by $name from $offset, ignore those $key-$value not match.
		 */
		public function fetch_element_with_attribute($name, $key, $value, $offset = 0) {
			if ($this->data == null) {
				return null;
			}
			
			$value = strtolower($value);
			$len = strlen($this->data);
			for (; $offset < $len;) {
				$element = $this->fetch_element($name, $offset);
				if ($element) {
					$attr = $element->attribute($key);
					if ($attr && strtolower($attr) == $value) {
						return $element;
					}
				}
			}
			// not found
			return null;
		}
		
	}
	
