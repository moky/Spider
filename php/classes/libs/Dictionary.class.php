<?php
	
	require_once('Object.class.php');
	
	
	class Dictionary extends Object implements ArrayAccess, Iterator, Countable {
		
		protected $array = [];
		
		function __construct($array = []) {
			parent::__construct();
			
			$this->array = $array;
		}
		
		function __isset($key) {
			return isset($this->array[$key]);
		}
		
		function __unset($key) {
			if ($this->__isset($key)) {
				unset($this->array[$key]);
			}
		}
		
		function __set($key, $value) {
			if ($key) {
				if ($value) {
					$this->array[$key] = $value; // INSERT or UPDATE
				} else {
					$this->__unset($key); // DELETE
				}
			} elseif ($value) {
				$this->array[] = $value; // APPEND
			}
		}
		
		function __get($key) {
			return $this->__isset($key) ? $this->array[$key] : null;
		}
		
		function __toString() {
			$str = '';
			foreach ($this->array as $key => $value) {
				$str .= ",\n\t\"$key\" : \"$value\"";
			}
			if ($str) {
				$str = substr($str, 1) . "\n";
			}
			//return get_class($this) . '::{' . $str . '}';
			return '{' . $str . '}';
		}
		
		// return the array
		function __toArray() {
			return $this->array;
		}
		
		// return all keys
		function keys() {
			return array_keys($this->array);
		}
		
		// return all values
		function values() {
			return array_values($this->array);
		}
		
		//
		//  Countable
		//
		
		function count() {
			return count($this->array);
		}
		
		//
		//  ArrayAccess
		//
		
		public function offsetExists($offset) {
			return $this->__isset($offset);
		}
		
		public function offsetGet($offset) {
			return $this->__get($offset);
		}
		
		public function offsetSet($offset, $value) {
			return $this->__set($offset, $value);
		}
		
		public function offsetUnset($offset) {
			return $this->__unset($offset);
		}
		
		//
		//  Iterator
		//
		
		public function current() {
			return current($this->array);
		}
		
		public function next() {
			return next($this->array);
		}
		
		public function key() {
			return key($this->array);
		}
		
		public function valid() {
			$value = current($this->array);
			return !empty($value);
		}
		
		public function rewind() {
			return reset($this->array);
		}
		
	}
	
	
