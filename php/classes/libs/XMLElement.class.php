<?php
	
	require_once('Log.class.php');
	require_once('Object.class.php');
	require_once('Dictionary.class.php');
	
	
	class XMLElement extends Object {
		
		protected $data = null;
		
		public $name = null;
		protected $attributes = null;
		
		function __construct($string) {
			parent::__construct();
			
			$this->data = trim($string);
			$this->name = self::fetchName($this->data);
			$this->attributes = new Dictionary();
		}
		
		function __toString() {
			return get_class($this) . '::\'' . $this->data . '\'';
		}
		
		/**
		 *  Description:
		 *      get element name from '<XXX '
		 */
		function name() {
			if (!$this->name == null && $this->data) {
				$this->name = self::fetchName($this->data);
			}
			return $this->name;
		}
		
		/**
		 *  Description:
		 *      get element attribute from '<... XXX="YYY" '
		 */
		function attribute($key) {
			if (!$key) {
				return null;
			} else {
				$key = strtolower($key);
			}
			$value = $this->attributes[$key];
			if (!$value && $this->data) {
				$value = self::fetchAttribute($key, $this->data);
				if ($value) {
					$this->attributes[$key] = $value;
				}
			}
			return $value;
		}
		
		//
		// protected:
		//
		
		protected function fetchName($data) {
			if (!preg_match('/\<\s*([^\s]*)/', $data, $matches)) {
				// not found
				return null;
			}
			if (count($matches) == 2) {
				return $matches[1];
			} else {
				Log::error('fetch element name error, string: ' . $data);
				return null;
			}
		}
		
		private function patternForAttr($key, $qmark) {
			return '/\s+(' . $key . ')\s*=\s*' . $qmark . '([^' . $qmark. ']*)' . $qmark. '/i';
		}
		
		protected function fetchAttribute($key, $data) {
			if (!preg_match(self::patternForAttr($key, '"'), $data, $matches)) {
				if (!preg_match(self::patternForAttr($key, '\''), $data, $matches)) {
					// not found
					return null;
				}
			}
			if (count($matches) == 3) {
				return $matches[2];
			} else {
				Log::error('fetch element attribute error, string: ' . $data . ', key: ' . $key);
				return null;
			}
		}
		
	}
	
	
