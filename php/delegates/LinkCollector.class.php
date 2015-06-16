<?php
	
	class LinkCollector {
		
		var $ignores = ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'js', 'css', 'ico'];
		
		var $html = null;
		var $base = null;
		
		function __construct($html, $url) {
			$this->html = $html;
			
			$url1 = new URL($url);
			$path = new Path($url1->path);
			
			$url2 = new URL();
			$url2->scheme = $url1->scheme;
			$url2->user   = $url1->user;
			$url2->pass   = $url1->pass;
			$url2->host   = $url1->host;
			$url2->port   = $url1->port;
			$url2->path   = $path->dirname;
			
			$this->base = $url2->__toString();
		}
		
		function process($href) {
			if (!$href) {
				return null;
			}
			
			// ignore 'javascript'
			if (preg_match('/^\s*(javascript)[^w]*/', $href) > 0) {
				//Log::info("ignore javascript: $href");
				return null;
			}
			
			// full URL
			$url = new URL($href);
			if ($url->scheme == null) {
				if ($this->base) {
					$href = $this->base . $href;
				} else {
					Log::error("base url cannot be empty");
					return null;
				}
			}
			
			// ignore resource files
			$path = new Path($href);
			$ext = $path->extension;
			if ($ext && in_array(strtolower($ext), $this->ignores)) {
				return null;
			}
			
			return $href;
		}
		
		/**
		 *
		 *  get all links: <a href="..."></a>
		 *
		 */
		function links() {
			$links = [];
			
			$html = new HTML($this->html);
			$len = strlen($this->html);
			for ($seek = 0; $seek < $len;) {
				// '<a ...'
				$a = $html->fetch_element('a', $seek);
				if (!$a) {
					// finished
					break;
				}
				
				// ' href="..."'
				$href = $a->fetch_attribute('href');
				if ($href) {
					$url = $this->process($href);
					if ($url) {
						array_push($links, $url);
					}
				}
			}
			
			return $links;
		}
		
	}
	
	
	
