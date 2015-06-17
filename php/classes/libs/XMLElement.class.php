<?php
	
	require_once('Log.class.php');
	require_once('Object.class.php');
	require_once('Dictionary.class.php');
	
	
	class XMLElement extends Object {
		
		protected $data = null;
		
		public $name = null;
		protected $attributes = null;
		
		public function __construct($string) {
			parent::__construct();
			
			$this->data = $string;
			$this->name = self::fetch_name($string);
			$this->attributes = new Dictionary();
		}
		
		public function __toString() {
			return get_class($this) . '::\'' . $this->data . '\'';
		}
		
		/**
		 *  Description:
		 *      get element name from '<XXX '
		 */
		public function name() {
			if (!$this->name == null && $this->data) {
				$this->name = self::fetch_name($this->data);
			}
			return $this->name;
		}
		
		/**
		 *  Description:
		 *      get element attribute from '<... XXX="YYY" '
		 */
		public function attribute($key) {
			if (!$key) {
				return null;
			} else {
				$key = strtolower($key);
			}
			$value = $this->attributes[$key];
			if (!$value && $this->data) {
				$value = self::fetch_attribute($key, $this->data);
				if ($value) {
					$this->attributes[$key] = $value;
				}
			}
			return $value;
		}
		
		//
		// protected:
		//
		
		protected function fetch_name($data) {
			$pattern = '/\<\s*([^\s]*)/';
			if (!preg_match($pattern, $data, $matches)) {
				// not found
				return null;
			}
			if (count($matches) == 2) {
				return $matches[1];
			} else {
				Log::error('failed to fetch element name: ' . $data);
				return null;
			}
		}
		
		protected function pattern_for_attribute($key, $qmark) {
			return '/\s+(' . $key . ')\s*=\s*' . $qmark . '([^' . $qmark. ']*)' . $qmark. '/i';
		}
		
		protected function fetch_attribute($key, $data) {
			$qmark = '"';
			$pattern = self::pattern_for_attribute($key, $qmark);
			if (!preg_match($pattern, $data, $matches)) {
				$qmark = '\'';
				$pattern = self::pattern_for_attribute($key, $qmark);
				if (!preg_match($pattern, $data, $matches)) {
					// not found
					return null;
				}
			}
			if (count($matches) == 3) {
				return $matches[2];
			} else {
				Log::error('failed to fetch element attribute: ' . $data . ', key: ' . $key);
				return null;
			}
		}
		
	}
	
	
