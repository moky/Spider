<?php
	
	require_once(dirname(__FILE__).'/../classes/libs/Log.class.php');
	require_once(dirname(__FILE__).'/../classes/libs/URL.class.php');
	require_once(dirname(__FILE__).'/../classes/libs/Path.class.php');
	require_once(dirname(__FILE__).'/../classes/libs/HTML.class.php');
	
	
	/**
	 *
	 *  collect links: <a href="URL">text</a>
	 *
	 */
	class LinkCollector {
		
		private $ignores = ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'js', 'css', 'ico'];
		
		private $html = null;
		private $base = null;
		
		public function __construct($html, $url) {
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
		
		private function process($href) {
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
		
		//
		//  get all links: <a href="..."></a>
		//
		public function links() {
			$links = [];
			
			$html = new HTML($this->html);
			$len = strlen($this->html);
			for ($offset = 0; $offset < $len;) {
				// '<a ...'
				$a = $html->fetchElement('a', $offset);
				if (!$a) {
					// finished
					break;
				}
				
				// ' href="..."'
				$href = $a->attribute('href');
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
	
	
	
