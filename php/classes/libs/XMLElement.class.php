<?php
	
	
	class XMLElement {
		
		protected $data = null;
		
		public function __construct($string) {
			$this->data = $string;
		}
		
		public function __toString() {
			return $this->data;
		}
		
		protected function pattern_for_attribute($key, $qmark) {
			return '/\s+(' . $key . ')\s*=\s*' . $qmark . '[^' . $qmark. ']*' . $qmark. '/i';
		}
		
		public function fetch_attribute($key) {
			$qmark = '"';
			$pattern = self::pattern_for_attribute($key, $qmark);
			if (preg_match($pattern, $this->data, $matches) <= 0) {
				$qmark = '\'';
				$pattern = self::pattern_for_attribute($key, $qmark);
				if (preg_match($pattern, $this->data, $matches) <= 0) {
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
		
	}
	
	
