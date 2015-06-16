<?php
	
	
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
			
			return $element;
		}
		
		protected function pattern_for_attribute($key, $qmark) {
			return '/\s+(' . $key . ')\s*=\s*' . $qmark . '[^' . $qmark. ']*' . $qmark. '/i';
		}
		
		public function fetch_attribute($element, $key) {
			
			$qmark = '"';
			$pattern = self::pattern_for_attribute($key, $qmark);
			if (preg_match($pattern, $element, $matches) <= 0) {
				$qmark = '\'';
				$pattern = self::pattern_for_attribute($key, $qmark);
				if (preg_match($pattern, $element, $matches) <= 0) {
					// not found
					return null;
				}
			}
			
			// get first match
			if (count($matches) <= 0) {
				echo __FILE__ . '"' . __LINE__ . " error!\n";
				return null;
			}
			$attr = $matches[0];
			
			// get value in quotation marks
			$pos = strpos($attr, $qmark);
			if ($pos === false) {
				echo __FILE__ . '"' . __LINE__ . " error!\n";
				return null;
			}
			$pos += 1;
			$attr = substr($attr, $pos);
			
			$pos = strrpos($attr, $qmark);
			if ($pos === false) {
				echo __FILE__ . '"' . __LINE__ . " error!\n";
				return null;
			}
			$attr = substr($attr, 0, $pos);
			
			return $attr;
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
				$element = self::fetch_element($name, $seek);
				if ($element) {
					$attr = self::fetch_attribute($element, $key);
					if ($attr && strtolower($attr) == $value) {
						return $element;
					}
				}
			}
			// not found
			return null;
		}
		
	}
	
