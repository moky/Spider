<?php
	
	class Dictionary implements ArrayAccess, Iterator, Countable {
		
		protected $array = [];
		
		function __construct($array = []) {
			$this->array = $array;
		}
		
		function __toString() {
			$str = '';
			foreach ($this->array as $key => $value) {
				$str .= "\t$key : $value,\n";
			}
			return "{\n$str}";
		}
		
		function __isset($key) {
			return $this->array && isset($this->array[$key]);
		}
		
		function __unset($key) {
			if ($this->__isset($key)) {
				unset($this->array[$key]);
			}
		}
		
		function __set($key, $value) {
			if ($key != null) {
				if ($value) {
					$this->array[$key] = $value;
				} else {
					$this->__unset($key);
				}
			}
		}
		
		function __get($key) {
			return $this->__isset($key) ? $this->array[$key] : null;
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
	
	
