<?php
	
	require_once('Dictionary.class.php');
	
	/**
	 *
	 *  URL format:
	 *
	 *      scheme://user:pass@host:port/dirs/filename.ext?query_string#fragment
	 *
	 */
	
	class URL {
		
		// public:
		var $scheme   = null;
		var $user     = null;
		var $pass     = null;
		var $host     = null;
		var $port     = null;
		var $path     = null;
		var $query    = null;
		var $fragment = null;
		
		// protected:
		protected $params = null;
		
		function __construct($string = null) {
			$dict = parse_url($string);
			if ($dict) {
				$dict = new Dictionary($dict);
				$this->scheme   = $dict->scheme;
				$this->user     = $dict->user;
				$this->pass     = $dict->pass;
				$this->host     = $dict->host;
				$this->port     = $dict->port;
				$this->path     = $dict->path;
				$this->query    = $dict->query;
				$this->fragment = $dict->fragment;
				
				$this->params   = self::parse_vars($this->query);
			}
		}
		
		function __toString() {
			// scheme
			$str = $this->scheme . '://';
			// user:pass
			if ($this->user) {
				$str .= $this->user;
				if ($this->pass) {
					$str .= ':' . $this->pass;
				}
				$str .= '@';
			}
			// host
			$str .= $this->host;
			// port
			if ($this->port > 0 && $this->port != $this->defaultPort($this->scheme)) {
				$str .= ':' . $this->port;
			}
			// path
			if ($this->path) {
				$str .= $this->path;
			}
			// query
			if ($this->params) {
				$query = self::build_vars($this->params->array);
				if ($query) {
					$str .= '?' . $query;
				}
			} elseif ($this->query) {
				$str .= '?' . $this->query;
			}
			// fragment
			if ($this->fragment) {
				$str .= '#' . $this->fragment;
			}
			return $str;
		}
		
		function parameter($key) {
			return $this->params ? $this->params->$key : null;
		}
		
		//
		// protected/private:
		//
		
		protected function defaultPort($scheme) {
			switch (strtolower($scheme)) {
				case 'ftp'    : return 21;
				//case 'ssh'    : return 22;
				//case 'telnet' : return 23;
				//case 'smtp'   : return 25;
				case 'http'   : return 80;
				//case 'pop3'   : return 110;
				case 'https'  : return 443;
			}
			return 0;
		}
		
		// en/decode
		private function encode_url_string($str) {
			return urlencode($str);
		}
		private function decode_url_string($str) {
			//return htmlspecialchars(urldecode($str));
			$str = preg_replace("/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode($str));
			return html_entity_decode($str, null, 'UTF-8');
		}
		
		protected function parse_vars($query) {
			if (!$query) {
				return null;
			}
			$params = [];
			$pairs = explode('&', $query);
			foreach ($pairs as $item) {
				if (strlen($item) == 0) {
					continue;
				}
				$kv = explode('=', $item, 2);
				if (count($kv) != 2) {
					continue;
				}
				$params[self::decode_url_string($kv[0])] = self::decode_url_string($kv[1]);
			}
			return new Dictionary($params);
		}
		
		protected function build_vars($array) {
			if (!$array || count($array) == 0) {
				return null;
			}
			$str = '';
			foreach ($array as $key => $value) {
				$str .= '&' . self::encode_url_string($key) . '=' . self::encode_url_string($value);
			}
			return substr($str, 1);
		}
		
	}
	
